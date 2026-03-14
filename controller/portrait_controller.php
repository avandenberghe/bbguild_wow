<?php
/**
 * Portrait sync AJAX controller
 *
 * Processes a batch of character portrait fetches per request.
 * Designed to be called repeatedly by JS until all portraits are synced.
 *
 * @package   avathar\bbguild_wow
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\controller;

use avathar\bbguild_wow\game\wow_api;
use phpbb\db\driver\driver_interface;
use Symfony\Component\HttpFoundation\JsonResponse;

class portrait_controller
{
	/** @var wow_api */
	protected $wow_api;

	/** @var driver_interface */
	protected $db;

	/** @var string */
	protected $players_table;

	/** @var string */
	protected $guild_table;

	/** @var string */
	protected $games_table;

	public function __construct(
		wow_api $wow_api,
		driver_interface $db,
		string $players_table,
		string $guild_table,
		string $games_table
	)
	{
		$this->wow_api = $wow_api;
		$this->db = $db;
		$this->players_table = $players_table;
		$this->guild_table = $guild_table;
		$this->games_table = $games_table;
	}

	/**
	 * Sync guild roster from Battle.net API and return result as JSON.
	 *
	 * @param int $guild_id
	 * @return JsonResponse
	 */
	public function sync_roster($guild_id)
	{
		$guild_id = (int) $guild_id;

		// Get guild data
		$sql = 'SELECT name, realm, region, min_armory, game_id FROM ' . $this->guild_table .
			' WHERE id = ' . $guild_id;
		$result = $this->db->sql_query($sql);
		$guild_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$guild_row || $guild_row['game_id'] !== 'wow')
		{
			return new JsonResponse(array('success' => false, 'message' => 'Guild not found or not a WoW guild.'));
		}

		// Get game API credentials
		$sql = 'SELECT apikey, privkey, apilocale, region FROM ' . $this->games_table .
			" WHERE game_id = 'wow'";
		$result = $this->db->sql_query($sql);
		$game_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$game_row || empty($game_row['apikey']))
		{
			return new JsonResponse(array('success' => false, 'message' => 'API credentials not configured.'));
		}

		$region = !empty($guild_row['region']) ? $guild_row['region'] : $game_row['region'];

		// Fetch guild data + roster
		$data = $this->wow_api->fetch_guild_data(
			$guild_row['name'],
			$guild_row['realm'],
			$region,
			array('members')
		);

		if (!is_array($data) || isset($data['code']))
		{
			$detail = isset($data['code']) ? sprintf('API error %d', $data['code']) : 'Empty response';
			return new JsonResponse(array('success' => false, 'message' => $detail));
		}

		// Process and save guild data
		$processed = $this->wow_api->process_guild_data($data, array('members'));
		$this->wow_api->save_guild_extension($guild_id, $processed);

		// Update core guild fields
		$update = array(
			'armoryresult' => 'OK',
			'faction'      => isset($processed['faction']) ? $processed['faction'] : 2,
		);
		if (isset($processed['emblempath']))
		{
			$update['emblemurl'] = $processed['emblempath'];
		}
		if (isset($processed['playercount']))
		{
			$update['players'] = $processed['playercount'];
		}
		$this->db->sql_query('UPDATE ' . $this->guild_table .
			' SET ' . $this->db->sql_build_array('UPDATE', $update) .
			' WHERE id = ' . $guild_id);

		// Sync members
		$member_count = 0;
		if (isset($data['members']))
		{
			$min_level = isset($guild_row['min_armory']) ? (int) $guild_row['min_armory'] : 0;
			$this->wow_api->sync_guild_members($data['members'], $guild_id, $region, $min_level);
			$member_count = count($data['members']);
		}

		return new JsonResponse(array(
			'success'      => true,
			'message'      => sprintf('Roster synced: %d members.', $member_count),
			'member_count' => $member_count,
		));
	}

	/**
	 * Process a batch of specialization syncs and return progress as JSON.
	 *
	 * @param int $guild_id
	 * @return JsonResponse
	 */
	public function sync_specs($guild_id)
	{
		$guild_id = (int) $guild_id;

		$sql = 'SELECT apikey, privkey, apilocale, region FROM ' . $this->games_table .
			" WHERE game_id = 'wow'";
		$result = $this->db->sql_query($sql);
		$game_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$game_row || empty($game_row['apikey']))
		{
			return new JsonResponse(array('error' => 'API credentials not configured', 'done' => true), 400);
		}

		$sql = 'SELECT region FROM ' . $this->guild_table . ' WHERE id = ' . $guild_id;
		$result = $this->db->sql_query($sql);
		$guild_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$region = (!empty($guild_row['region'])) ? $guild_row['region'] : $game_row['region'];

		// Count total and remaining
		$sql = 'SELECT COUNT(*) AS total FROM ' . $this->players_table .
			' WHERE player_guild_id = ' . $guild_id .
			" AND game_id = 'wow' AND player_status = 1";
		$result = $this->db->sql_query($sql);
		$total = (int) $this->db->sql_fetchfield('total');
		$this->db->sql_freeresult($result);

		$sql = 'SELECT COUNT(*) AS remaining FROM ' . $this->players_table .
			' WHERE player_guild_id = ' . $guild_id .
			" AND game_id = 'wow' AND player_status = 1" .
			" AND (player_spec = '' OR player_spec IS NULL)";
		$result = $this->db->sql_query($sql);
		$remaining_before = (int) $this->db->sql_fetchfield('remaining');
		$this->db->sql_freeresult($result);

		if ($remaining_before === 0)
		{
			return new JsonResponse(array(
				'done' => true, 'fetched' => 0, 'total' => $total, 'remaining' => 0,
				'message' => 'All specs are up to date.',
			));
		}

		$sync_result = $this->wow_api->sync_specs(
			$guild_id, $region,
			$game_row['apikey'], $game_row['apilocale'], $game_row['privkey']
		);

		$sql = 'SELECT COUNT(*) AS remaining FROM ' . $this->players_table .
			' WHERE player_guild_id = ' . $guild_id .
			" AND game_id = 'wow' AND player_status = 1" .
			" AND (player_spec = '' OR player_spec IS NULL)";
		$result = $this->db->sql_query($sql);
		$remaining_after = (int) $this->db->sql_fetchfield('remaining');
		$this->db->sql_freeresult($result);

		return new JsonResponse(array(
			'done'      => $remaining_after === 0,
			'fetched'   => $sync_result['count'],
			'total'     => $total,
			'remaining' => $remaining_after,
			'message'   => $sync_result['message'],
		));
	}

	/**
	 * Process a batch of portrait syncs and return progress as JSON.
	 *
	 * @param int $guild_id
	 * @return JsonResponse
	 */
	public function sync($guild_id)
	{
		$guild_id = (int) $guild_id;

		// Get game API credentials
		$sql = 'SELECT apikey, privkey, apilocale, region FROM ' . $this->games_table .
			" WHERE game_id = 'wow'";
		$result = $this->db->sql_query($sql);
		$game_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$game_row || empty($game_row['apikey']))
		{
			return new JsonResponse(array(
				'error' => 'API credentials not configured',
				'done' => true,
			), 400);
		}

		// Get guild region (fall back to game region)
		$sql = 'SELECT region FROM ' . $this->guild_table .
			' WHERE id = ' . $guild_id;
		$result = $this->db->sql_query($sql);
		$guild_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$region = (!empty($guild_row['region'])) ? $guild_row['region'] : $game_row['region'];

		// Count total and remaining
		$sql = 'SELECT COUNT(*) AS total FROM ' . $this->players_table .
			' WHERE player_guild_id = ' . $guild_id .
			" AND game_id = 'wow' AND player_status = 1";
		$result = $this->db->sql_query($sql);
		$total = (int) $this->db->sql_fetchfield('total');
		$this->db->sql_freeresult($result);

		$sql = 'SELECT COUNT(*) AS remaining FROM ' . $this->players_table .
			' WHERE player_guild_id = ' . $guild_id .
			" AND game_id = 'wow' AND player_status = 1" .
			" AND (player_portrait_url = '' OR player_portrait_url IS NULL OR player_portrait_url LIKE 'http%')";
		$result = $this->db->sql_query($sql);
		$remaining_before = (int) $this->db->sql_fetchfield('remaining');
		$this->db->sql_freeresult($result);

		if ($remaining_before === 0)
		{
			return new JsonResponse(array(
				'done'      => true,
				'fetched'   => 0,
				'failed'    => 0,
				'total'     => $total,
				'remaining' => 0,
				'message'   => 'All portraits are up to date.',
			));
		}

		// Run a batch
		$sync_result = $this->wow_api->sync_portraits(
			$guild_id,
			$region,
			$game_row['apikey'],
			$game_row['apilocale'],
			$game_row['privkey']
		);

		// Recount remaining
		$sql = 'SELECT COUNT(*) AS remaining FROM ' . $this->players_table .
			' WHERE player_guild_id = ' . $guild_id .
			" AND game_id = 'wow' AND player_status = 1" .
			" AND (player_portrait_url = '' OR player_portrait_url IS NULL OR player_portrait_url LIKE 'http%')";
		$result = $this->db->sql_query($sql);
		$remaining_after = (int) $this->db->sql_fetchfield('remaining');
		$this->db->sql_freeresult($result);

		return new JsonResponse(array(
			'done'      => $remaining_after === 0,
			'fetched'   => $sync_result['count'],
			'total'     => $total,
			'remaining' => $remaining_after,
			'message'   => $sync_result['message'],
		));
	}
}
