<?php
/**
 * bbGuild WoW plugin - Add player_spec column
 *
 * @package   avathar\bbguild_wow
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\migrations\v200a3;

class player_spec extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild_wow\migrations\basics\data'];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'bb_players', 'player_spec');
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'bb_players' => [
					'player_spec' => ['VCHAR:100', ''],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'bb_players' => [
					'player_spec',
				],
			],
		];
	}
}
