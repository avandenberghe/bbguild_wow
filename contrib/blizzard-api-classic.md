# Blizzard API — World of Warcraft Classic

Reference documentation for the WoW Classic Game Data and Profile APIs.

Source: https://community.developer.battle.net/documentation/world-of-warcraft-classic

## Namespaces

Namespaces allow JSON documents to be published contextually in relation to a specific patch or point in time.

### Categories

| Category | Description |
|----------|-------------|
| Static | Game system data (achievements, items). Changes per-patch. |
| Dynamic | In-game event data (auctions, realm status). Changes frequently. |
| Profile | Character/account data (achievements, guild roster). Updated on logout (characters) or regular interval (guilds). |

### Available Namespaces

| Game Version | Static | Dynamic | Profile |
|---|---|---|---|
| Classic Era | `static-classic1x-{region}` | `dynamic-classic1x-{region}` | `profile-classic1x-{region}` |
| MoP Classic (Progression) | `static-classic-{region}` | `dynamic-classic-{region}` | `profile-classic-{region}` |
| BC Classic (Anniversary) | `static-classicann-{region}` | `dynamic-classicann-{region}` | `profile-classicann-{region}` |

### Region Identifiers

| Region | Identifier |
|--------|-----------|
| North America | `us` |
| Europe | `eu` |
| Korea | `kr` |
| Taiwan | `tw` |
| China | `cn` |

Example: `static-classic1x-us`, `profile-classic-eu`

### Specifying a Namespace

| Method | Format | Example |
|--------|--------|---------|
| Header | `Battlenet-Namespace: {namespace}` | `Battlenet-Namespace: static-classic-us` |
| Query Parameter | `?namespace={namespace}` | `?namespace=static-classic-us` |

## Host Names

| Region | Host |
|--------|------|
| US, EU, KR, TW | `{region}.api.blizzard.com` |
| China | `gateway.battlenet.com.cn` |

## Localization

### Supported Locales

| Locale | Value |
|--------|-------|
| English (US) | `en_US` |
| Spanish (Mexico) | `es_MX` |
| Portuguese | `pt_BR` |
| German | `de_DE` |
| English (GB) | `en_GB` |
| Spanish (Spain) | `es_ES` |
| French | `fr_FR` |
| Italian | `it_IT` |
| Russian | `ru_RU` |
| Korean | `ko_KR` |
| Chinese (Traditional) | `zh_TW` |
| Chinese (Simplified) | `zh_CN` |

Without `?locale=`, the API returns all localized values. With `?locale=ko_KR`, only that locale's value is returned (flattened).

## Game Data APIs

### Auction House API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/connected-realm/{connectedRealmId}/auctions/index` |
| GET | `/data/wow/connected-realm/{connectedRealmId}/auctions/{auctionHouseId}` |

### Connected Realm API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/connected-realm/index` |
| GET | `/data/wow/connected-realm/{connectedRealmId}` |
| GET | `/data/wow/search/connected-realm` |

### Creature API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/creature-family/index` |
| GET | `/data/wow/creature-family/{creatureFamilyId}` |
| GET | `/data/wow/creature-type/index` |
| GET | `/data/wow/creature-type/{creatureTypeId}` |
| GET | `/data/wow/creature/{creatureId}` |
| GET | `/data/wow/search/creature` |
| GET | `/data/wow/media/creature-display/{creatureDisplayId}` |
| GET | `/data/wow/media/creature-family/{creatureFamilyId}` |

### Guild Crest API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/guild-crest/index` |
| GET | `/data/wow/media/guild-crest/border/{borderId}` |
| GET | `/data/wow/media/guild-crest/emblem/{emblemId}` |

### Item API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/item-class/index` |
| GET | `/data/wow/item-class/{itemClassId}` |
| GET | `/data/wow/item-class/{itemClassId}/item-subclass/{itemSubclassId}` |
| GET | `/data/wow/item/{itemId}` |
| GET | `/data/wow/media/item/{itemId}` |
| GET | `/data/wow/search/item` |

### Media Search API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/search/media` |

### Playable Class API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/playable-class/index` |
| GET | `/data/wow/playable-class/{classId}` |
| GET | `/data/wow/media/playable-class/{playableClassId}` |

### Playable Race API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/playable-race/index` |
| GET | `/data/wow/playable-race/{playableRaceId}` |

### Power Type API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/power-type/index` |
| GET | `/data/wow/power-type/{powerTypeId}` |

### PvP Season API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/pvp-season/index` |
| GET | `/data/wow/pvp-season/{pvpSeasonId}` |
| GET | `/data/wow/pvp-region/index` |
| GET | `/data/wow/pvp-region/{pvpRegionId}/pvp-season/index` |
| GET | `/data/wow/pvp-region/{pvpRegionId}/pvp-season/{pvpSeasonId}` |
| GET | `/data/wow/pvp-region/{pvpRegionId}/pvp-season/{pvpSeasonId}/pvp-leaderboard/index` |
| GET | `/data/wow/pvp-region/{pvpRegionId}/pvp-season/{pvpSeasonId}/pvp-leaderboard/{pvpBracket}` |
| GET | `/data/wow/pvp-region/{pvpRegionId}/pvp-season/{pvpSeasonId}/pvp-reward/index` |

### Realm API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/realm/index` |
| GET | `/data/wow/realm/{realmSlug}` |
| GET | `/data/wow/search/realm` |

### Region API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/region/index` |
| GET | `/data/wow/region/{regionId}` |

### WoW Token API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/token/index` (CN only) |

## Profile APIs

### Account Profile API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/user/wow` |
| GET | `/profile/user/wow/protected-character/{realmId}-{characterId}` |

### Character Appearance API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/appearance` |

### Character Equipment API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/equipment` |

### Character Hunter Pets API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/hunter-pets` |

### Character Media API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/character-media` |

### Character Profile API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/status` |

### Character PvP API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/pvp-bracket/{pvpBracket}` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/pvp-summary` |

### Character Specializations API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/specializations` |

### Character Statistics API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/statistics` |

### Character Achievements API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/achievements` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/achievements/statistics` |

### Guild API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/guild/{realmSlug}/{nameSlug}` |
| GET | `/data/wow/guild/{realmSlug}/{nameSlug}/activity` |
| GET | `/data/wow/guild/{realmSlug}/{nameSlug}/achievements` |
| GET | `/data/wow/guild/{realmSlug}/{nameSlug}/roster` |

## Media Documents

Media documents link to web assets (images, renders, icons) for API resources. Example:

```json
{
  "assets": [
    {
      "key": "icon",
      "value": "https://render-us.worldofwarcraft.com/icons/56/inv_sword_39.jpg"
    }
  ]
}
```

**Important:** Blizzard recommends storing copies of media assets locally rather than linking directly to Blizzard resources.
