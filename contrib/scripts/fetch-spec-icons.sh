#!/usr/bin/env bash
#
# fetch-spec-icons.sh — download WoW specialization icons from Battle.net
# and save them under our stable filename convention.
#
# Mirrors the catalog from game/wow_provider.php::spec_catalog().
# 39 specs across 13 classes.
#
# USAGE
#   ./fetch-spec-icons.sh --client-id ID --client-secret SECRET \
#       [--region eu|us|kr|tw] [--out DIR] [--keep-jpg]
#
#   # Or read credentials from a wowkeys.txt file with format:
#   #   client_id=...
#   #   client_secret=...
#   ./fetch-spec-icons.sh --keys ../../wowkeys.txt
#
# The script:
#   1. Fetches an OAuth token via client_credentials grant
#   2. For each of the 39 specs, calls /data/wow/playable-specialization/{id}/media
#   3. Downloads the icon (Blizzard serves JPG)
#   4. Converts to PNG using sips (macOS), magick, or convert (ImageMagick)
#   5. Saves as <our-filename>.png to --out (default: images/spec_icons/)
#
# Pass --keep-jpg to skip the JPG→PNG conversion (useful if no ImageMagick
# / sips is installed; you can convert later or update the catalog to .jpg).

set -euo pipefail

# ---------------------- Argument parsing ----------------------------------

CLIENT_ID=""
CLIENT_SECRET=""
KEYS_FILE=""
REGION="eu"
OUT_DIR=""
KEEP_JPG=0

while [[ $# -gt 0 ]]; do
    case "$1" in
        --client-id)     CLIENT_ID="$2"; shift 2 ;;
        --client-secret) CLIENT_SECRET="$2"; shift 2 ;;
        --keys)          KEYS_FILE="$2"; shift 2 ;;
        --region)        REGION="$2"; shift 2 ;;
        --out)           OUT_DIR="$2"; shift 2 ;;
        --keep-jpg)      KEEP_JPG=1; shift ;;
        -h|--help)
            sed -n '2,30p' "$0"
            exit 0
            ;;
        *)
            echo "Unknown argument: $1" >&2
            exit 2
            ;;
    esac
done

# Resolve script dir for the default --out path.
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PLUGIN_ROOT="$( cd "$SCRIPT_DIR/../.." && pwd )"
OUT_DIR="${OUT_DIR:-$PLUGIN_ROOT/images/spec_icons}"

# Read credentials from --keys file if provided.
if [[ -n "$KEYS_FILE" ]]; then
    if [[ ! -f "$KEYS_FILE" ]]; then
        echo "Keys file not found: $KEYS_FILE" >&2
        exit 1
    fi
    # Source as shell vars (lines like client_id=... / client_secret=...)
    while IFS='=' read -r key val; do
        case "$key" in
            client_id)     CLIENT_ID="$val" ;;
            client_secret) CLIENT_SECRET="$val" ;;
        esac
    done < "$KEYS_FILE"
fi

if [[ -z "$CLIENT_ID" || -z "$CLIENT_SECRET" ]]; then
    echo "Missing --client-id / --client-secret (or --keys FILE)" >&2
    exit 1
fi

case "$REGION" in
    eu|us|kr|tw) ;;
    *) echo "Invalid --region $REGION (use eu, us, kr, or tw)" >&2; exit 1 ;;
esac

mkdir -p "$OUT_DIR"

# ---------------------- Conversion tool detection -------------------------

CONVERT_CMD=""
if [[ "$KEEP_JPG" -eq 0 ]]; then
    if command -v magick >/dev/null 2>&1; then
        CONVERT_CMD="magick"
    elif command -v convert >/dev/null 2>&1; then
        CONVERT_CMD="convert"
    elif command -v sips >/dev/null 2>&1; then
        CONVERT_CMD="sips"
    else
        echo "Note: no JPG→PNG converter found (install ImageMagick or use --keep-jpg)." >&2
        echo "      Falling back to --keep-jpg behavior." >&2
        KEEP_JPG=1
    fi
fi

# ---------------------- Spec catalog -------------------------------------
# spec_id|filename — names mirror wow_provider::spec_catalog()
SPECS=(
    "71|warrior_arms"
    "72|warrior_fury"
    "73|warrior_protection"
    "65|paladin_holy"
    "66|paladin_protection"
    "70|paladin_retribution"
    "253|hunter_beast_mastery"
    "254|hunter_marksmanship"
    "255|hunter_survival"
    "259|rogue_assassination"
    "260|rogue_outlaw"
    "261|rogue_subtlety"
    "256|priest_discipline"
    "257|priest_holy"
    "258|priest_shadow"
    "250|deathknight_blood"
    "251|deathknight_frost"
    "252|deathknight_unholy"
    "262|shaman_elemental"
    "263|shaman_enhancement"
    "264|shaman_restoration"
    "62|mage_arcane"
    "63|mage_fire"
    "64|mage_frost"
    "265|warlock_affliction"
    "266|warlock_demonology"
    "267|warlock_destruction"
    "268|monk_brewmaster"
    "270|monk_mistweaver"
    "269|monk_windwalker"
    "102|druid_balance"
    "103|druid_feral"
    "104|druid_guardian"
    "105|druid_restoration"
    "577|demonhunter_havoc"
    "581|demonhunter_vengeance"
    "1467|evoker_devastation"
    "1468|evoker_preservation"
    "1473|evoker_augmentation"
)

# ---------------------- OAuth token --------------------------------------

echo ">> Fetching OAuth token from oauth.battle.net …"
TOKEN_JSON=$(curl -s -X POST https://oauth.battle.net/token \
    -u "${CLIENT_ID}:${CLIENT_SECRET}" \
    -d 'grant_type=client_credentials')

# Minimal JSON parse without jq dependency.
TOKEN=$(printf '%s' "$TOKEN_JSON" | sed -n 's/.*"access_token":"\([^"]*\)".*/\1/p')
if [[ -z "$TOKEN" ]]; then
    echo "Failed to obtain access token. Response was:" >&2
    echo "$TOKEN_JSON" >&2
    exit 1
fi
echo "   token acquired (length=${#TOKEN})"

# ---------------------- Per-spec download -------------------------------

API_HOST="https://${REGION}.api.blizzard.com"
NAMESPACE="static-${REGION}"
SUCCESS=0
FAILED=0

convert_to_png() {
    local jpg="$1"
    local png="$2"
    case "$CONVERT_CMD" in
        magick|convert)
            "$CONVERT_CMD" "$jpg" "$png"
            ;;
        sips)
            sips -s format png "$jpg" --out "$png" >/dev/null
            ;;
    esac
}

for entry in "${SPECS[@]}"; do
    SPEC_ID="${entry%%|*}"
    NAME="${entry##*|}"
    URL="${API_HOST}/data/wow/playable-specialization/${SPEC_ID}/media?namespace=${NAMESPACE}"

    MEDIA_JSON=$(curl -s -H "Authorization: Bearer ${TOKEN}" "$URL")
    ICON_URL=$(printf '%s' "$MEDIA_JSON" \
        | sed -n 's/.*"key":"icon","value":"\([^"]*\)".*/\1/p' \
        | head -n 1)

    if [[ -z "$ICON_URL" ]]; then
        echo "  ✗ ${NAME} (id ${SPEC_ID}) — no icon URL returned"
        echo "    response: $(printf '%s' "$MEDIA_JSON" | head -c 200)"
        FAILED=$((FAILED + 1))
        continue
    fi

    EXT="${ICON_URL##*.}"
    JPG_PATH="$OUT_DIR/${NAME}.${EXT}"
    PNG_PATH="$OUT_DIR/${NAME}.png"

    if ! curl -s -f -o "$JPG_PATH" "$ICON_URL"; then
        echo "  ✗ ${NAME} (id ${SPEC_ID}) — download failed: $ICON_URL"
        FAILED=$((FAILED + 1))
        continue
    fi

    if [[ "$KEEP_JPG" -eq 0 && "$EXT" != "png" ]]; then
        if convert_to_png "$JPG_PATH" "$PNG_PATH" 2>/dev/null; then
            rm -f "$JPG_PATH"
            echo "  ✓ ${NAME}.png"
        else
            echo "  ✓ ${NAME}.${EXT} (conversion failed; kept JPG)"
        fi
    else
        echo "  ✓ ${NAME}.${EXT}"
    fi
    SUCCESS=$((SUCCESS + 1))
done

echo ""
echo "Done. ${SUCCESS} downloaded, ${FAILED} failed. Output: ${OUT_DIR}"
