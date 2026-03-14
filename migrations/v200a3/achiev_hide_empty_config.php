<?php
/**
 * bbGuild WoW plugin - Add config for hiding empty achievement categories
 *
 * @package   avathar\bbguild_wow
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\migrations\v200a3;

class achiev_hide_empty_config extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild_wow\migrations\v200a3\achievement_categories'];
	}

	public function effectively_installed()
	{
		return $this->config->offsetExists('bbguild_achiev_hide_empty');
	}

	public function update_data()
	{
		return [
			['config.add', ['bbguild_achiev_hide_empty', 1]],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['bbguild_achiev_hide_empty']],
		];
	}
}
