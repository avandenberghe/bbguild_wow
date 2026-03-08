<?php
/**
 * Battle.net WoW Static Game Data API
 *
 * Provides access to playable class and race indexes for ID→name resolution.
 *
 * @package   bbguild_wow v2.0
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    Andreas Vandenberghe <sajaki@avathar.be>
 * @link      https://develop.battle.net/
 */

namespace avathar\bbguild_wow\api;

/**
 * Static game data resource (playable classes, races).
 *
 * @package avathar\bbguild_wow\api
 */
class battlenet_static_data extends battlenet_resource
{
	/** @var array */
	protected $methods_allowed = array('*');

	/** @var string */
	protected $endpoint = 'data/wow';

	/**
	 * Fetch the playable class index.
	 *
	 * @return array
	 */
	public function getPlayableClasses(): array
	{
		return $this->consume('playable-class/index', array());
	}

	/**
	 * Fetch the playable race index.
	 *
	 * @return array
	 */
	public function getPlayableRaces(): array
	{
		return $this->consume('playable-race/index', array());
	}

	/**
	 * Fetch guild crest emblem media by ID.
	 *
	 * Returns the assets array containing the render URL for the emblem PNG.
	 *
	 * @param int $emblem_id
	 * @return array
	 */
	public function getEmblemMedia(int $emblem_id): array
	{
		return $this->consume('media/guild-crest/emblem/' . $emblem_id, array());
	}

	/**
	 * Fetch guild crest border media by ID.
	 *
	 * Returns the assets array containing the render URL for the border PNG.
	 *
	 * @param int $border_id
	 * @return array
	 */
	public function getBorderMedia(int $border_id): array
	{
		return $this->consume('media/guild-crest/border/' . $border_id, array());
	}
}
