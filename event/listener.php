<?php
/**
 * bbGuild WoW plugin - Event listener
 *
 * Provides WoW-specific template variables (achievement points, armory URL)
 * for the bbGuild sidebar when the current guild is a WoW guild.
 *
 * @package   avathar\bbguild_wow
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\event;

use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var template */
	private $template;

	/** @var driver_interface */
	private $db;

	/** @var string */
	private $guild_wow_table;

	/**
	 * @param template         $template
	 * @param driver_interface $db
	 * @param string           $guild_wow_table
	 */
	public function __construct(template $template, driver_interface $db, $guild_wow_table)
	{
		$this->template = $template;
		$this->db = $db;
		$this->guild_wow_table = $guild_wow_table;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'core.page_header_after' => 'add_wow_guild_vars',
		);
	}

	/**
	 * When the current page is a bbGuild page with a WoW guild,
	 * load WoW-specific guild data and assign template variables.
	 *
	 * @param \phpbb\event\data $event
	 */
	public function add_wow_guild_vars($event)
	{
		// Only act when bbGuild has set GAME_ID and GUILD_ID template vars
		$tpldata = $this->template->retrieve_vars(array('GAME_ID', 'GUILD_ID'));

		$game_id = isset($tpldata['GAME_ID']) ? $tpldata['GAME_ID'] : '';
		$guild_id = isset($tpldata['GUILD_ID']) ? (int) $tpldata['GUILD_ID'] : 0;

		if ($game_id !== 'wow' || $guild_id <= 0)
		{
			return;
		}

		$sql = 'SELECT achievementpoints, guildarmoryurl
				FROM ' . $this->guild_wow_table . '
				WHERE guild_id = ' . $guild_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row)
		{
			$this->template->assign_vars(array(
				'ACHIEV'              => $row['achievementpoints'],
				'ARMORY'              => $row['guildarmoryurl'],
				'ARMORY_URL'          => $row['guildarmoryurl'],
				'S_DISPLAY_ACHIEVEMENTS' => !empty($row['achievementpoints']),
			));
		}
	}
}
