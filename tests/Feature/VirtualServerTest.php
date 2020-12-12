<?php

namespace DSG\PostScriptumRCON\Tests\Feature;

use DSG\PostScriptumRCON\Data\ServerConnectionInfo;
use DSG\PostScriptumRCON\PostScriptumServer;
use DSG\PostScriptumRCON\Tests\Runners\TestingCommandRunner;

class VirtualServerTest extends \DSG\PostScriptumRCON\Tests\TestCase {
    private PostScriptumServer $postScriptumServer;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->postScriptumServer = new PostScriptumServer(new ServerConnectionInfo('', 0, ''), new TestingCommandRunner());
    }

    /**
     * Verifies the currentMap can properly be retrieved.
     *
     * @return void
     */
    public function test_current_map()
    {
        $this->assertSame('Heelsum Single 01', $this->postScriptumServer->currentMap());
    }

    /**
     * Verifies the nextMap can properly be retrieved.
     *
     * @return void
     */
    public function test_next_map()
    {
        $this->assertSame('Driel Single 01', $this->postScriptumServer->nextMap());
    }

    /**
     * Verifies the player list can properly be retrieved.
     *
     * @return void
     */
    public function test_list_players()
    {
        $players = $this->postScriptumServer->listPlayers();

        $this->assertCount(77, $players);
    }

    /**
     * Verifies the disconnected player list can properly be retrieved.
     *
     * @return void
     */
    public function test_list_disconnected_players()
    {
        $playerList = $this->postScriptumServer->listDisconnectedPlayers();

        $this->assertCount(3, $playerList);

        foreach ($playerList as $player) {
            if ($player->getId() === 88) {
                $this->assertSame(195, $player->getDisconnectedSince());
            } else if ($player->getId() === 84) {
                $this->assertSame(108, $player->getDisconnectedSince());
            } else if ($player->getId() === 42) {
                $this->assertSame(3, $player->getDisconnectedSince());
            }
        }
    }

    /**
     * Verifies the broadcast command does work properly
     * 
     * @return void
     */
    public function test_admin_Broadcast()
    {
        $this->assertTrue($this->postScriptumServer->adminBroadcast('Hello World!'));
    }

    /**
     * Verifies the change map command does work properly
     * 
     * @return void
     */
    public function test_admin_change_map()
    {
        $this->assertTrue($this->postScriptumServer->adminChangeMap('Heelsum Single 01'));
    }

    /**
     * Verifies the set next map command does work properly
     * 
     * @return void
     */
    public function test_admin_set_next_map()
    {
        $this->assertTrue($this->postScriptumServer->adminSetNextMap('Heelsum Single 01'));
    }

    /**
     * Verifies the restart match command does work properly
     * 
     * @return void
     */
    public function test_admin_restart_match()
    {
        $this->assertTrue($this->postScriptumServer->adminRestartMatch());
    }

    /**
     * Verifies the end match command does work properly
     * 
     * @return void
     */
    public function test_admin_end_match()
    {
        $this->assertTrue($this->postScriptumServer->adminEndMatch());
    }

    /**
     * Verifies the admin set max num players command does work properly
     * 
     * @return void
     */
    public function test_admin_set_max_num_players()
    {
        $this->assertTrue($this->postScriptumServer->adminSetMaxNumPlayers(78));
    }

    /**
     * Verifies the admin set max num players command does work properly
     * 
     * @return void
     */
    public function test_admin_set_password()
    {
        $this->assertTrue($this->postScriptumServer->adminSetServerPassword('secret'));
    }

    /**
     * Verifies the kick command does work properly
     * 
     * @return void
     */
    public function test_admin_kick()
    {
        $this->assertTrue($this->postScriptumServer->adminKick('Marcel', 'Test'));
    }

    /**
     * Verifies the kick by id command does work properly
     * 
     * @return void
     */
    public function test_admin_kick_by_id()
    {
        $this->assertTrue($this->postScriptumServer->adminKickById(1, 'Test'));
    }

    /**
     * Verifies the ban command does work properly
     * 
     * @return void
     */
    public function test_admin_ban()
    {
        $this->assertTrue($this->postScriptumServer->adminBan('Marcel', '1h', 'Test'));
    }

    /**
     * Verifies the ban by id command does work properly
     * 
     * @return void
     */
    public function test_admin_ban_by_id()
    {
        $this->assertTrue($this->postScriptumServer->adminBanById(1, '1h', 'Test'));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_admin_force_team_change()
    {
        $this->assertTrue($this->postScriptumServer->adminForceTeamChange('Test'));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_admin_force_team_change_by_id()
    {
        $this->assertTrue($this->postScriptumServer->adminForceTeamChange(0));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_admin_disband_squad()
    {
        $this->assertTrue($this->postScriptumServer->adminForceTeamChange(1, 1));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_admin_remove_player_from_squad()
    {
        $this->assertTrue($this->postScriptumServer->adminRemovePlayerFromSquad('Test'));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_admin_remove_player_from_squad_by_id()
    {
        $this->assertTrue($this->postScriptumServer->adminRemovePlayerFromSquadById(0));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_admin_warn()
    {
        $this->assertTrue($this->postScriptumServer->adminWarn('Test', 'Hello World!'));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_admin_warn_by_id()
    {
        $this->assertTrue($this->postScriptumServer->adminWarnById(0, 'Hello World!'));
    }

    /**
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_disconnect()
    {
        $this->assertNull($this->postScriptumServer->disconnect());
    }
}