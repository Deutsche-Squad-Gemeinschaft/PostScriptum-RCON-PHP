<?php

namespace DSG\SquadRCON\Tests\Feature;

use DSG\SquadRCON\Data\ServerConnectionInfo;
use DSG\SquadRCON\SquadServer;
use DSG\SquadRCON\Tests\Runners\TestingCommandRunner;

class SquadServerTest extends \DSG\SquadRCON\Tests\TestCase {
    private SquadServer $squadServer;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->squadServer = new SquadServer(new ServerConnectionInfo('', 0, ''), new TestingCommandRunner());
    }

    /**
     * Verifies the currentMap can properly be retrieved.
     *
     * @return void
     */
    public function test_current_map()
    {
        $this->assertSame('Al Basrah AAS v1', $this->squadServer->currentMap());
    }

    /**
     * Verifies the nextMap can properly be retrieved.
     *
     * @return void
     */
    public function test_next_map()
    {
        $this->assertSame('Belaya AAS v1', $this->squadServer->nextMap());
    }

    /**
     * Verifies the player list can properly be retrieved.
     *
     * @return void
     */
    public function test_list_players()
    {
        $playerList = $this->squadServer->listPlayers();

        $this->assertCount(77, $playerList);
    }

    /**
     * Verifies the team/squad list can properly be retrieved.
     *
     * @return void
     */
    public function test_list_squads()
    {
        $teams = $this->squadServer->listSquads();

        $squadCount = 0;

        foreach ($teams as $team) {
            if ($team->getId() === 1) {
                $this->assertSame('United States Army', $team->getName());
                $this->assertCount(8, $team->getSquads());

                foreach ($team->getSquads() as $squad) {
                    if ($squad->getId() === 1) {
                        $this->assertSame('HELI', $squad->getName());
                    } else if ($squad->getId() === 2) {
                        $this->assertSame('HELI', $squad->getName());
                    } else if ($squad->getId() === 3) {
                        $this->assertSame('CMD Squad', $squad->getName());
                    } else if ($squad->getId() === 4) {
                        $this->assertSame('MBT', $squad->getName());
                    } else if ($squad->getId() === 5) {
                        $this->assertSame('BRADLEY', $squad->getName());
                    } else if ($squad->getId() === 6) {
                        $this->assertSame('STRYKER', $squad->getName());
                    } else if ($squad->getId() === 7) {
                        $this->assertSame('BOS SACHEN MACHEN', $squad->getName());
                    } else if ($squad->getId() === 8) {
                        $this->assertSame('RUNNING SQUAD', $squad->getName());
                    }
                }
            } else {
                $this->assertSame('Russian Ground Forces', $team->getName());
                $this->assertCount(10, $team->getSquads());
            }

            $squadCount += count($team->getSquads());
        }
        
        $this->assertSame(18, $squadCount);
    }

    /**
     * Verifies the server population can properly be retrieved.
     *
     * @return void
     */
    public function test_server_population()
    {
        $teams = $this->squadServer->serverPopulation();

        $squadCount = 0;
        $playerCount = 0;

        foreach ($teams as $team) {
            $squadCount += count($team->getSquads());

            $teamPlayerCount = count($team->getPlayers());
            foreach ($team->getSquads() as $squad) {
                $teamPlayerCount += count($squad->getPlayers());
            }
            $playerCount += $teamPlayerCount;

            if ($team->getId() === 1) {
                $this->assertSame('United States Army', $team->getName());
                $this->assertCount(8, $team->getSquads());
                $this->assertSame(38, $teamPlayerCount);
            } else {
                $this->assertSame('Russian Ground Forces', $team->getName());
                $this->assertCount(10, $team->getSquads());
                $this->assertSame(39, $teamPlayerCount);
            }
        }
        
        $this->assertSame(18, $squadCount);
        $this->assertSame(77, $playerCount);
    }

    /**
     * Verifies the broadcast command does work properly
     * 
     * @return void
     */
    public function test_admin_Broadcast()
    {
        $this->assertTrue($this->squadServer->adminBroadcast('Hello World!'));
    }

    /**
     * Verifies the change map command does work properly
     * 
     * @return void
     */
    public function test_admin_change_map()
    {
        $this->assertTrue($this->squadServer->adminChangeMap('Al Basrah AAS v1'));
    }

    /**
     * Verifies the set next map command does work properly
     * 
     * @return void
     */
    public function test_admin_set_next_map()
    {
        $this->assertTrue($this->squadServer->adminSetNextMap('Al Basrah AAS v1'));
    }
}