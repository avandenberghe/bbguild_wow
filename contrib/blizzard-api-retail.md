# Blizzard API — World of Warcraft (Retail)

Reference documentation for the WoW Retail Game Data and Profile APIs.

Source: https://community.developer.battle.net/documentation/world-of-warcraft

## Namespaces

### Categories

| Category | Description |
|----------|-------------|
| Static | Game system data (achievements, items). Changes per-patch. |
| Dynamic | In-game event data (leaderboards, auctions, WoW Token). Changes frequently. |
| Profile | Character/account data (achievements, guild roster). Updated on logout (characters) or regular interval (guilds). |

### Available Namespaces

| Category | Namespace |
|----------|-----------|
| Static | `static-{region}` |
| Dynamic | `dynamic-{region}` |
| Profile | `profile-{region}` |

### Region Identifiers

| Region | Identifier |
|--------|-----------|
| North America | `us` |
| Europe | `eu` |
| Korea | `kr` |
| Taiwan | `tw` |
| China | `cn` |

Example: `static-us`, `dynamic-cn`, `profile-tw`

### Specifying a Namespace

| Method | Format | Example |
|--------|--------|---------|
| Header | `Battlenet-Namespace: {namespace}` | `Battlenet-Namespace: static-us` |
| Query Parameter | `?namespace={namespace}` | `?namespace=static-us` |

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

## Character Renders

Character render images are generated on logout. Available at:

```
/profile/wow/character/{realm-slug}/{character-name}/character-media
```

### Fallback Images

For missing avatars, use the `alt` query parameter:

```
{avatar-url}?alt=/shadow/avatar/{race-id}-{gender-id}.jpg
```

Gender IDs: Male = `0`, Female = `1`

Race IDs available from `/data/wow/playable-race/index`.

## Game Data APIs

### Achievement API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/achievement/index` |
| GET | `/data/wow/achievement/{achievementId}` |
| GET | `/data/wow/media/achievement/{achievementId}` |
| GET | `/data/wow/achievement-category/index` |
| GET | `/data/wow/achievement-category/{achievementCategoryId}` |

### Auction House API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/connected-realm/{connectedRealmId}/auctions` |
| GET | `/data/wow/auctions/commodities` |

### Azerite Essence API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/azerite-essence/index` |
| GET | `/data/wow/azerite-essence/{azeriteEssenceId}` |
| GET | `/data/wow/search/azerite-essence` |
| GET | `/data/wow/media/azerite-essence/{azeriteEssenceId}` |

### Connected Realm API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/connected-realm/index` |
| GET | `/data/wow/connected-realm/{connectedRealmId}` |
| GET | `/data/wow/search/connected-realm` |

### Covenant API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/covenant/index` |
| GET | `/data/wow/covenant/{covenantId}` |
| GET | `/data/wow/media/covenant/{covenantId}` |
| GET | `/data/wow/covenant/soulbind/index` |
| GET | `/data/wow/covenant/soulbind/{soulbindId}` |
| GET | `/data/wow/covenant/conduit/index` |
| GET | `/data/wow/covenant/conduit/{conduitId}` |

### Creature API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/creature/{creatureId}` |
| GET | `/data/wow/search/creature` |
| GET | `/data/wow/media/creature-display/{creatureDisplayId}` |
| GET | `/data/wow/creature-family/index` |
| GET | `/data/wow/creature-family/{creatureFamilyId}` |
| GET | `/data/wow/media/creature-family/{creatureFamilyId}` |
| GET | `/data/wow/creature-type/index` |
| GET | `/data/wow/creature-type/{creatureTypeId}` |

### Guild Crest API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/guild-crest/index` |
| GET | `/data/wow/media/guild-crest/border/{borderId}` |
| GET | `/data/wow/media/guild-crest/emblem/{emblemId}` |

### Heirloom API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/heirloom/index` |
| GET | `/data/wow/heirloom/{heirloomId}` |

### Housing Decor API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/decor/index` |
| GET | `/data/wow/decor/{decorId}` |
| GET | `/data/wow/search/decor` |
| GET | `/data/wow/fixture/index` |
| GET | `/data/wow/fixture/{fixtureId}` |
| GET | `/data/wow/search/fixture` |
| GET | `/data/wow/fixture-hook/index` |
| GET | `/data/wow/fixture-hook/{fixtureHookId}` |
| GET | `/data/wow/search/fixture-hook` |
| GET | `/data/wow/room/index` |
| GET | `/data/wow/room/{roomId}` |
| GET | `/data/wow/search/room` |

### Item API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/item/{itemId}` |
| GET | `/data/wow/search/item` |
| GET | `/data/wow/media/item/{itemId}` |
| GET | `/data/wow/item-class/index` |
| GET | `/data/wow/item-class/{itemClassId}` |
| GET | `/data/wow/item-set/index` |
| GET | `/data/wow/item-set/{itemSetId}` |
| GET | `/data/wow/item-class/{itemClassId}/item-subclass/{itemSubclassId}` |

### Item Appearance API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/item-appearance/{appearanceId}` |
| GET | `/data/wow/search/item-appearance` |
| GET | `/data/wow/item-appearance/set/index` |
| GET | `/data/wow/item-appearance/set/{appearanceSetId}` |
| GET | `/data/wow/item-appearance/slot/index` |
| GET | `/data/wow/item-appearance/slot/{slotType}` |

### Journal API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/journal-expansion/index` |
| GET | `/data/wow/journal-expansion/{journalExpansionId}` |
| GET | `/data/wow/journal-encounter/index` |
| GET | `/data/wow/journal-encounter/{journalEncounterId}` |
| GET | `/data/wow/search/journal-encounter` |
| GET | `/data/wow/journal-instance/index` |
| GET | `/data/wow/journal-instance/{journalInstanceId}` |
| GET | `/data/wow/media/journal-instance/{journalInstanceId}` |

### Media Search API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/search/media` |

### Modified Crafting API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/modified-crafting/index` |
| GET | `/data/wow/modified-crafting/category/index` |
| GET | `/data/wow/modified-crafting/category/{categoryId}` |
| GET | `/data/wow/modified-crafting/reagent-slot-type/index` |
| GET | `/data/wow/modified-crafting/reagent-slot-type/{slotTypeId}` |

### Mount API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/mount/index` |
| GET | `/data/wow/mount/{mountId}` |
| GET | `/data/wow/search/mount` |

### Mythic Keystone Affix API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/keystone-affix/index` |
| GET | `/data/wow/keystone-affix/{keystoneAffixId}` |
| GET | `/data/wow/media/keystone-affix/{keystoneAffixId}` |

### Mythic Keystone Dungeon API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/mythic-keystone/index` |
| GET | `/data/wow/mythic-keystone/dungeon/index` |
| GET | `/data/wow/mythic-keystone/dungeon/{dungeonId}` |
| GET | `/data/wow/mythic-keystone/period/index` |
| GET | `/data/wow/mythic-keystone/period/{periodId}` |
| GET | `/data/wow/mythic-keystone/season/index` |
| GET | `/data/wow/mythic-keystone/season/{seasonId}` |

### Mythic Keystone Leaderboard API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/connected-realm/{connectedRealmId}/mythic-leaderboard/index` |
| GET | `/data/wow/connected-realm/{connectedRealmId}/mythic-leaderboard/{dungeonId}/period/{period}` |

### Mythic Raid Leaderboard API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/leaderboard/hall-of-fame/{raid}/{faction}` |

### Neighborhood API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/neighborhood-map/index` |
| GET | `/data/wow/neighborhood-map/{neighborhoodMapId}` |
| GET | `/data/wow/neighborhood-map/{neighborhoodMapId}/neighborhood/{neighborhoodId}` |

### Pet API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/pet/index` |
| GET | `/data/wow/pet/{petId}` |
| GET | `/data/wow/media/pet/{petId}` |
| GET | `/data/wow/pet-ability/index` |
| GET | `/data/wow/pet-ability/{petAbilityId}` |
| GET | `/data/wow/media/pet-ability/{petAbilityId}` |

### Playable Class API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/playable-class/index` |
| GET | `/data/wow/playable-class/{classId}` |
| GET | `/data/wow/media/playable-class/{playableClassId}` |
| GET | `/data/wow/playable-class/{classId}/pvp-talent-slots` |

### Playable Race API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/playable-race/index` |
| GET | `/data/wow/playable-race/{playableRaceId}` |

### Playable Specialization API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/playable-specialization/index` |
| GET | `/data/wow/playable-specialization/{specId}` |
| GET | `/data/wow/media/playable-specialization/{specId}` |

### Power Type API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/power-type/index` |
| GET | `/data/wow/power-type/{powerTypeId}` |

### Profession API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/profession/index` |
| GET | `/data/wow/profession/{professionId}` |
| GET | `/data/wow/media/profession/{professionId}` |
| GET | `/data/wow/profession/{professionId}/skill-tier/{skillTierId}` |
| GET | `/data/wow/recipe/{recipeId}` |
| GET | `/data/wow/media/recipe/{recipeId}` |

### PvP Season API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/pvp-season/index` |
| GET | `/data/wow/pvp-season/{pvpSeasonId}` |
| GET | `/data/wow/pvp-season/{pvpSeasonId}/pvp-leaderboard/index` |
| GET | `/data/wow/pvp-season/{pvpSeasonId}/pvp-leaderboard/{pvpBracket}` |
| GET | `/data/wow/pvp-season/{pvpSeasonId}/pvp-reward/index` |

### PvP Tier API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/pvp-tier/index` |
| GET | `/data/wow/pvp-tier/{pvpTierId}` |
| GET | `/data/wow/media/pvp-tier/{pvpTierId}` |

### Quest API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/quest/index` |
| GET | `/data/wow/quest/{questId}` |
| GET | `/data/wow/quest/category/index` |
| GET | `/data/wow/quest/category/{questCategoryId}` |
| GET | `/data/wow/quest/area/index` |
| GET | `/data/wow/quest/area/{questAreaId}` |
| GET | `/data/wow/quest/type/index` |
| GET | `/data/wow/quest/type/{questTypeId}` |

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

### Reputations API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/reputation-faction/index` |
| GET | `/data/wow/reputation-faction/{reputationFactionId}` |
| GET | `/data/wow/reputation-tiers/index` |
| GET | `/data/wow/reputation-tiers/{reputationTiersId}` |

### Spell API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/spell/{spellId}` |
| GET | `/data/wow/media/spell/{spellId}` |
| GET | `/data/wow/search/spell` |

### Talent API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/talent-tree/index` |
| GET | `/data/wow/talent-tree/{talentTreeId}/playable-specialization/{specId}` |
| GET | `/data/wow/talent-tree/{talentTreeId}` |
| GET | `/data/wow/talent/index` |
| GET | `/data/wow/talent/{talentId}` |
| GET | `/data/wow/pvp-talent/index` |
| GET | `/data/wow/pvp-talent/{pvpTalentId}` |

### Tech Talent API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/tech-talent-tree/index` |
| GET | `/data/wow/tech-talent-tree/{techTalentTreeId}` |
| GET | `/data/wow/tech-talent/index` |
| GET | `/data/wow/tech-talent/{techTalentId}` |
| GET | `/data/wow/media/tech-talent/{techTalentId}` |

### Title API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/title/index` |
| GET | `/data/wow/title/{titleId}` |

### Toy API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/toy/index` |
| GET | `/data/wow/toy/{toyId}` |

### WoW Token API

| Method | Endpoint |
|--------|----------|
| GET | `/data/wow/token/index` (US, EU, KR, TW) |
| GET | `/data/wow/token/index` (CN) |

## Profile APIs

### Account Profile API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/user/wow` |
| GET | `/profile/user/wow/protected-character/{realmId}-{characterId}` |
| GET | `/profile/user/wow/collections` |
| GET | `/profile/user/wow/collections/decor` |
| GET | `/profile/user/wow/collections/heirlooms` |
| GET | `/profile/user/wow/collections/mounts` |
| GET | `/profile/user/wow/collections/pets` |
| GET | `/profile/user/wow/collections/toys` |
| GET | `/profile/user/wow/collections/transmogs` |

### Character Achievements API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/achievements` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/achievements/statistics` |

### Character Appearance API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/appearance` |

### Character Collections API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/collections` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/collections/decor` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/collections/heirlooms` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/collections/mounts` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/collections/pets` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/collections/toys` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/collections/transmogs` |

### Character Encounters API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/encounters` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/encounters/dungeons` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/encounters/raids` |

### Character Equipment API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/equipment` |

### Character House API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/house/house-{houseNumber}` |

### Character Hunter Pets API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/hunter-pets` |

### Character Media API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/character-media` |

### Character Mythic Keystone Profile API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/mythic-keystone-profile` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/mythic-keystone-profile/season/{seasonId}` |

### Character Professions API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/professions` |

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

### Character Quests API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/quests` |
| GET | `/profile/wow/character/{realmSlug}/{characterName}/quests/completed` |

### Character Reputations API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/reputations` |

### Character Soulbinds API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/soulbinds` |

### Character Specializations API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/specializations` |

### Character Statistics API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/statistics` |

### Character Titles API

| Method | Endpoint |
|--------|----------|
| GET | `/profile/wow/character/{realmSlug}/{characterName}/titles` |

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
