<?php

namespace DSG\SquadRCON\Runners;

use DSG\SquadRCON\Contracts\ServerCommandRunner;
use DSG\SquadRCON\Data\ServerConnectionInfo;
use xPaw\SourceQuery\SourceQuery;

class SquadCommandRunner implements ServerCommandRunner {
    /** @var SourceQuery */
    private $sourceQuery;

    /**
     * SquadServer constructor.
     * @param $host
     * @param $port
     * @param $password
     * @param float $timeout
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function __construct(ServerConnectionInfo $info)
    {
        /* Initialize the Query class */
        $this->sourceQuery = new SourceQuery();

        /* Connect to the Server */
        $this->sourceQuery->Connect($info->host, $info->port, $info->timeout);

        /* Set the RCON password */
        $this->sourceQuery->SetRconPassword($info->password);
    }
    
    /**
     * ListSquads command. Returns an array
     * of Teams containing Squads. The output
     * can be given to the listPlayers method
     * to add and reference the Player instances.
     *
     * @return Team[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listSquads() : string
    {
        return $this->sourceQuery->Rcon("ListSquads");
    }

    /**
     * ListPlayers command, returns an array
     * of Player instances. The output of
     * ListSquads can be piped into it to
     * assign the Players to their Team/Squad.
     *
     * @param array $teams
     * @return Player[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listPlayers() : string
    {
        /* Execute the ListPlayers command and get the response */
        return $this->sourceQuery->Rcon("ListPlayers");
    }

    /**
     * ShowNextMap command.
     * Gets the current and next map.
     * 
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function showNextMap() : string
    {
        return $this->sourceQuery->Rcon("ShowNextMap");
    }

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBroadcast(string $msg) : bool
    {
        return $this->_consoleCommand('AdminBroadcast', $msg, 'Message broadcasted');
    }

    /**
     * AdminRestartMatch command.
     * Broadcasts the given message on the server.
     *
     * @return boolean
     */
    function adminRestartMatch() : bool
    {
        return $this->_consoleCommand('AdminRestartMatch', '', 'Game restarted');
    }

    /**
     * AdminRestartMatch command.
     * Broadcasts the given message on the server.
     *
     * @return boolean
     */
    function adminEndMatch() : bool
    {
        return $this->_consoleCommand('AdminEndMatch', '', 'Match ended');
    }

    /**
     * AdminChangeMap command
     * Immediately changes the current map to the given map.
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminChangeMap(string $map) : bool
    {
        return $this->_consoleCommand('AdminChangeMap', $map, 'Changed map to');
    }

    /**
     * AdminSetNextMap command.
     * Temporarily overwrites the next map in the
     * MapRotations, effecively changing the next map.
     * 
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetNextMap(string $map) : bool
    {
        return $this->_consoleCommand('AdminSetNextMap', $map, 'Set next map to');
    }

    /**
     * Helper method to run Console commands with an expected response.
     * 
     * @param string $cmd
     * @param string $param
     * @param string $expected
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function _consoleCommand(string $cmd, string $param, string $expected) : bool
    {
        $response = $this->sourceQuery->Rcon($cmd . ' ' . $param);
        return substr($response, 0, strlen($expected)) == $expected;
    }
}