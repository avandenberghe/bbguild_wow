<?php
/**
 * bbGuild WoW Extension — add player equipment table
 *
 * @package   avathar\bbguild_wow
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\migrations\v200b3;

class add_player_equipment extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguild_wow\migrations\v200b2\release_2_0_0_b2',
		];
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'bb_player_equipment' => [
					'COLUMNS' => [
						'player_id'   => ['UINT', 0],
						'slot_type'   => ['VCHAR:30', ''],
						'item_id'     => ['UINT', 0],
						'item_name'   => ['VCHAR_UNI:255', ''],
						'item_level'  => ['USINT', 0],
						'quality'     => ['VCHAR:20', ''],
						'icon_url'    => ['VCHAR:255', ''],
						'last_update' => ['TIMESTAMP', 0],
					],
					'PRIMARY_KEY' => ['player_id', 'slot_type'],
					'KEYS' => [
						'pid' => ['INDEX', ['player_id']],
					],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'bb_player_equipment',
			],
		];
	}
}
