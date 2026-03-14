<?php
/**
 * bbguild_wow language file [Spanish]
 *
 * @package   phpBB Extension - bbguild_wow
 * @copyright 2009 bbguild
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge(
	$lang, array(
	'WOWAPI' => 'WoW Armory',
	'WOWAPIKEY' => 'Client ID',
	'WOWAPIKEY_EXPLAIN' => 'Crea un cliente API en <a href="https://develop.battle.net/access/clients">develop.battle.net/access/clients</a> para obtener tu Client ID.',
	'WOWPRIVKEY' => 'Client Secret',
	'WOWPRIVKEY_EXPLAIN' => 'El Client Secret de tu cliente API de Battle.net. Requerido para el acceso a la API.',
	'WOWAPILOCALE' => 'Idioma',
	'WOWAPILOCALE_EXPLAIN' => 'Los recursos de la API de Battle.net proporcionan cadenas localizadas usando el parámetro locale. Los locales disponibles varían según la región.',
	'WOWAPI_LOCALE_NOTALLOWED' => 'Locale ilegal %s: elige uno según tu región de WoW: en_GB, en_US, de_DE, es_ES, fr_FR, it_IT, pt_PT, pt_BR, o ru_RU',
	'WOWAPI_KEY_MISSING' => 'Crea un cliente API en <a href="https://develop.battle.net/access/clients">develop.battle.net</a> e introduce tu Client ID y Client Secret.',
	'WOWAPI_TOKEN_FAILED' => 'No se pudo obtener el token de acceso OAuth de Battle.net. Verifica tu Client ID y Client Secret.',
	'WOWAPI_METH_NOTALLOWED' => 'Método no permitido.',
	'WOWAPI_REGION_NOTALLOWED' => 'Región no permitida.',
	'WOWAPI_API_NOTIMPLEMENTED' => 'API no permitida.',
	'WOWAPI_NO_REALMS' => 'No se especificó ningún reino.',
	'WOWAPI_NO_GUILD' => 'No se especificó el nombre de la hermandad.',
	'WOWAPI_INVALID_FIELD' => 'Campo solicitado no válido: %s',
	'WOWAPI_NO_CHARACTER' => 'No se especificó el nombre del personaje.',
	'CHARACTERAPICALL' => 'Actualizar jugadores desde la API de personajes',
	'CALL_BATTLENET_CHAR_API' => 'Llamar a la API de personajes de Battle.net para esta hermandad. Desactiva si lastModified fue hace más de 90 días, reactiva si es menos de 90 y el estado de desactivación del personaje fue \'API\'.',
	'ARM_SHOWACH' => 'Mostrar puntos de logro',
	'ARM_SHOWACH_EXPLAIN' => 'Mostrar los totales de puntos de logro en la lista de la hermandad.',
	'ARM_ACHIEV_HIDE_EMPTY' => 'Ocultar categorías vacías',
	'ARM_ACHIEV_HIDE_EMPTY_EXPLAIN' => 'Ocultar categorías de logros sin progreso en el portal. Los usuarios pueden alternar desde la interfaz.',

	// Portal module names
	'BBGUILD_PORTAL_ACHIEVEMENTS' => 'Logros',
	'BBGUILD_PORTAL_GUILD_NEWS'   => 'Noticias del gremio',

	// Achievements module
	'ACHIEV_PROGRESS_OVERVIEW' => 'Resumen de progreso',
	'ACHIEV_TOTAL_COMPLETED'   => 'Total completados',
	'ACHIEV_RECENTLY_EARNED'   => 'Obtenidos recientemente',
	'ACHIEV_POINTS_TOTAL'      => 'Puntos de logro',
));
