<?php
/**
 * bbGuild WoW Extension — add player_render_url column
 *
 * Stores the full-body character render from the Battle.net Character Media API.
 *
 * @package   avathar\bbguild_wow
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\migrations\v200b3;

class add_player_render_url extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguild_wow\migrations\v200b3\add_player_equipment',
		];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'bb_players' => [
					'player_render_url' => ['VCHAR', ''],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'bb_players' => [
					'player_render_url',
				],
			],
		];
	}
}
