<?php
/**
 * WoW Game Provider
 *
 * Registers World of Warcraft as a game plugin with bbGuild core.
 *
 * @package   bbguild_wow v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\game;

use avathar\bbguild\model\games\game_provider_interface;
use avathar\bbguild\model\games\game_install_interface;
use avathar\bbguild\model\games\game_api_interface;

/**
 * Class wow_provider
 *
 * @package avathar\bbguild_wow\game
 */
class wow_provider implements game_provider_interface
{
	/** @var wow_installer */
	private $installer;

	/** @var wow_api */
	private $api;

	/** @var \phpbb\extension\manager */
	private $ext_manager;

	/**
	 * @param wow_installer             $installer
	 * @param wow_api                   $api
	 * @param \phpbb\extension\manager  $ext_manager
	 */
	public function __construct(wow_installer $installer, wow_api $api, \phpbb\extension\manager $ext_manager)
	{
		$this->installer = $installer;
		$this->api = $api;
		$this->ext_manager = $ext_manager;
	}

	/**
	 * @inheritdoc
	 */
	public function get_game_id(): string
	{
		return 'wow';
	}

	/**
	 * @inheritdoc
	 */
	public function get_game_name(): string
	{
		return 'World of Warcraft';
	}

	/**
	 * @inheritdoc
	 */
	public function get_installer(): game_install_interface
	{
		return $this->installer;
	}

	/**
	 * @inheritdoc
	 */
	public function get_boss_base_url(): string
	{
		return 'http://www.wowhead.com/?npc=%s';
	}

	/**
	 * @inheritdoc
	 */
	public function get_zone_base_url(): string
	{
		return 'http://www.wowhead.com/?zone=%s';
	}

	/**
	 * @inheritdoc
	 */
	public function get_images_path(): string
	{
		return $this->ext_manager->get_extension_path('avathar/bbguild_wow', true) . 'images/';
	}

	/**
	 * @inheritdoc
	 */
	public function has_api(): bool
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function get_api(): ?game_api_interface
	{
		return $this->api;
	}

	/**
	 * @inheritdoc
	 */
	public function get_regions(): array
	{
		return array(
			'us'  => 'US',
			'eu'  => 'EU',
			'kr'  => 'KR',
			'tw'  => 'TW',
			'sea' => 'SEA',
		);
	}

	/**
	 * @inheritdoc
	 */
	public function get_api_locales(): array
	{
		return array(
			'eu'  => array('en_GB', 'de_DE', 'es_ES', 'fr_FR', 'it_IT', 'pl_PL', 'pt_PT', 'ru_RU'),
			'us'  => array('en_US', 'es_MX', 'pt_BR'),
			'kr'  => array('ko_KR'),
			'tw'  => array('zh_TW'),
			'sea' => array('en_US'),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function get_armor_types(): array
	{
		return array(
			'CLOTH'   => 'Cloth',
			'LEATHER' => 'Leather',
			'MAIL'    => 'Mail',
			'PLATE'   => 'Plate',
		);
	}
}
