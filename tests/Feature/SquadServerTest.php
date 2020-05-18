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
}