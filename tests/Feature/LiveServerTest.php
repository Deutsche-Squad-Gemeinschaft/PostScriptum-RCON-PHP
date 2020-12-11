<?php

namespace DSG\PostScriptumRCON\Tests\Feature;

use DSG\PostScriptumRCON\Data\ServerConnectionInfo;
use DSG\PostScriptumRCON\PostScriptumServer;

class LiveServerTest extends \DSG\PostScriptumRCON\Tests\TestCase {
    private PostScriptumServer $postScriptumServer;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->postScriptumServer = new PostScriptumServer(new ServerConnectionInfo('172.17.0.2', 21114, 'secret'));
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
     * Verifies the set next map command does work properly
     * 
     * @return void
     */
    public function test_admin_set_next_map()
    {
        $this->assertTrue($this->postScriptumServer->adminSetNextMap('Oosterbeek Single 01'));
    }

    /**
     * Verifies the nextMap can properly be retrieved.
     *
     * @return void
     */
    public function test_next_map()
    {
        $this->assertSame('Oosterbeek Single 01', $this->postScriptumServer->nextMap());
    }

    /**
     * Verifies the player list can properly be retrieved.
     *
     * @return void
     */
    public function test_list_players()
    {
        $players = $this->postScriptumServer->listPlayers();

        $this->assertCount(0, $players);
    }

    /**
     * Verifies the disconnected player list can properly be retrieved.
     *
     * @return void
     */
    public function test_list_disconnected_players()
    {
        $playerList = $this->postScriptumServer->listDisconnectedPlayers();

        $this->assertCount(0, $playerList);
    }

    /**
     * Verifies the team/squad list can properly be retrieved.
     *
     * @return void
     */
    public function test_list_squads()
    {
        $teams = $this->postScriptumServer->listSquads();

        foreach ($teams as $team) {
            $this->assertSame(0, count($team->getSquads()));
        }
    }

    /**
     * Verifies the server population can properly be retrieved.
     *
     * @return void
     */
    public function test_server_population()
    {
        $teams = $this->postScriptumServer->serverPopulation();

        $squadCount = 0;
        $playerCount = 0;
        foreach ($teams as $team) {
            $squadCount += count($team->getSquads());
            $teamPlayerCount = count($team->getPlayers());
            foreach ($team->getSquads() as $squad) {
                $teamPlayerCount += count($squad->getPlayers());
            }
            $playerCount += $teamPlayerCount;
        }
        
        $this->assertSame(0, $squadCount);
        $this->assertSame(0, $playerCount);
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
        $this->assertTrue($this->postScriptumServer->adminChangeMap('Driel Single 02'));
    }

    /**
     * Verifies the restart match command does work properly
     * 
     * @return void
     */
    public function test_admin_restart_match()
    {
        $this->assertTrue($this->postScriptumServer->adminRestartMatch());

        sleep(60);
    }

    /**
     * Verifies the end match command does work properly
     * 
     * @return void
     */
    public function test_admin_end_match()
    {
        $this->assertTrue($this->postScriptumServer->adminEndMatch());

        sleep(60);
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
     * Verifies the disconnect method works without any exception
     * 
     * @return void
     */
    public function test_squad_server_disconnect()
    {
        $this->assertNull($this->postScriptumServer->disconnect());
    }
}