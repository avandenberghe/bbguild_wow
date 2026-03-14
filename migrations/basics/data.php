<?php
/**
 * bbGuild WoW plugin - Data seeding migration
 *
 * Seeds World of Warcraft factions, classes, races, and roles
 * by calling the existing installer service.
 *
 * @package   avathar\bbguild_wow
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\migrations\basics;

class data extends \phpbb\db\migration\container_aware_migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguild\migrations\basics\schema',
			'\avathar\bbguild_wow\migrations\basics\schema',
		];
	}

	public function effectively_installed()
	{
		$games_table = $this->table_prefix . 'bb_games';

		if (!$this->db_tools->sql_table_exists($games_table))
		{
			return false;
		}

		$sql = 'SELECT COUNT(*) AS cnt FROM ' . $games_table . " WHERE game_id = 'wow'";
		$result = $this->db->sql_query($sql);
		$count = (int) $this->db->sql_fetchfield('cnt');
		$this->db->sql_freeresult($result);

		return $count > 0;
	}

	public function update_data()
	{
		return [
			['config.add', ['bbguild_show_achiev', 0]],
			['custom', [[$this, 'seed_game_data']]],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['bbguild_show_achiev']],
			['custom', [[$this, 'remove_game_data']]],
			['custom', [[$this, 'remove_wow_players_and_guilds']]],
		];
	}

	public function seed_game_data()
	{
		$installer = $this->get_installer();
		$installer->install($this->get_table_names(), 'wow', 'World of Warcraft', '', '', 'us');
	}

	public function remove_game_data()
	{
		$installer = $this->get_installer();
		$installer->uninstall($this->get_table_names(), 'wow', 'World of Warcraft');
	}

	public function remove_wow_players_and_guilds()
	{
		$players_table = $this->table_prefix . 'bb_players';
		$guild_table = $this->table_prefix . 'bb_guild';
		$ranks_table = $this->table_prefix . 'bb_ranks';
		$guild_wow_table = $this->table_prefix . 'bb_guild_wow';

		// Delete WoW players
		$this->db->sql_query("DELETE FROM $players_table WHERE game_id = 'wow'");

		// Get WoW guild IDs (guilds that have no remaining players from other games)
		$sql = "SELECT g.id FROM $guild_table g
			WHERE g.id > 0
			AND g.game_id = 'wow'
			AND NOT EXISTS (
				SELECT 1 FROM $players_table p
				WHERE p.player_guild_id = g.id AND p.game_id <> 'wow'
			)";
		$result = $this->db->sql_query($sql);
		$guild_ids = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$guild_ids[] = (int) $row['id'];
		}
		$this->db->sql_freeresult($result);

		if (!empty($guild_ids))
		{
			$this->db->sql_query('DELETE FROM ' . $ranks_table .
				' WHERE ' . $this->db->sql_in_set('guild_id', $guild_ids));
			$this->db->sql_query('DELETE FROM ' . $guild_table .
				' WHERE ' . $this->db->sql_in_set('id', $guild_ids));
		}

		// Clean up guild_wow table
		if ($this->db_tools->sql_table_exists($guild_wow_table))
		{
			$this->db->sql_query("DELETE FROM $guild_wow_table");
		}

		// Clean up downloaded portraits
		$upload_path = $this->config['upload_path'];
		$portrait_dir = $this->phpbb_root_path . $upload_path . '/bbguild_wow/';
		if (is_dir($portrait_dir))
		{
			$files = glob($portrait_dir . 'portraits/*.jpg');
			if ($files)
			{
				array_map('unlink', $files);
			}
			@rmdir($portrait_dir . 'portraits');
			@rmdir($portrait_dir);
		}
	}

	private function get_installer()
	{
		return new \avathar\bbguild_wow\game\wow_installer(
			$this->container->get('dbal.conn'),
			$this->container->get('cache.driver'),
			$this->container->get('config'),
			$this->container->get('user')
		);
	}

	private function get_table_names()
	{
		return [
			'bb_games_table'     => $this->table_prefix . 'bb_games',
			'bb_factions_table'  => $this->table_prefix . 'bb_factions',
			'bb_classes_table'   => $this->table_prefix . 'bb_classes',
			'bb_races_table'     => $this->table_prefix . 'bb_races',
			'bb_gameroles_table' => $this->table_prefix . 'bb_gameroles',
			'bb_language_table'  => $this->table_prefix . 'bb_language',
			'bb_players_table'   => $this->table_prefix . 'bb_players',
		];
	}
}
