<?php

namespace DSG\SquadRCON;

use DSG\SquadRCON\Data\Team;
use DSG\SquadRCON\Data\Squad;
use DSG\SquadRCON\Data\Player;
use DSG\SquadRCON\Services\RCon;

class SquadServer
{
    const SQUAD_SOCKET_TIMEOUT_SECONDS = 0.5;

    /** @var RCon */
    private $rcon;

    /**
     * SquadServer constructor.
     * @param $host
     * @param $port
     * @param $password
     * @param float $timeout
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function __construct($host, $port, $password, $timeout = SquadServer::SQUAD_SOCKET_TIMEOUT_SECONDS)
    {
        $this->rcon = new RCon($host, $port, $password, $timeout);
    }

    /**
     * @return Team[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function serverPopulation() : array
    {
        /* Get the current Teams and their Squads */
        $teams = $this->listSquads();

        /* Get the currently connected players, feed listSquads output to reference Teams/Squads */
        $this->currentPlayers($teams);

        return $teams;
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
    public function listSquads() : array
    {
        /** @var Team[] $teams */
        $teams = [];

        /** @var Squad[] $squads */
        $squads = [];

        $resSquads = $this->rcon->execute("ListSquads");
        $linesSquads = explode("\n", $resSquads);

        /** @var Team The current team */
        $currentTeam = null;
        foreach ($linesSquads as $lineSquad) {
            $matches = [];
            if (preg_match('/^Team ID: ([1|2]) \((.*)\)/', $lineSquad, $matches) > 0) {
                /* Initialize a new Team */
                $team = new Team(intval($matches[1]), $matches[2]);

                /* Add to the lookup */
                $teams[$team->getId()] = $team;
                
                /* Initialize squad lookup array */
                $squads[$team] = [];

                /* Set as current team */
                $currentTeam = $team;
            } else if (preg_match('/^ID: (\d{1,}) \| Name: (.*?) \| Size: (\d) \| Locked: (True|False)/', $lineSquad, $matches) > 0) {
                /* Initialize a new Squad */
                $squad = new Squad(intval($matches[1]), $matches[2], intval($matches[3]), $matches[4] === 'True', $currentTeam);
                
                /* Reference Team */
                $currentTeam->addSquad($squad);

                /* Add to the squads lookup */
                $squads[$currentTeam->getId()][$squad->getId()] = $squad;
            }
        }

        return $teams;
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
     * @deprecated 0.1.3 Use listPlayers instead
     */
    public function currentPlayers(array &$teams = null) : array
    {
        return $this->listPlayers($teams);
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
    public function listPlayers(array &$teams = null) : array
    {
        /* Initialize an empty output array */
        $players = [];

        /* Execute the ListPlayers command and get the response */
        $response = $this->rcon->execute("ListPlayers");

        /* Process each individual line */
        foreach (explode("\n", $response) as $line) {
            /* Initialize an empty array and try to get info form line */
            $matches = [];
            if (preg_match('/^ID: (\d{1,}) \| SteamID: (\d{17}) \| Name: (.*?) \| Team ID: (1|2|N\/A) \| Squad ID: (\d{1,}|N\/A)/', $line, $matches)) {
                /* Initialize new Player instance */
                $player = new Player(intval($matches[1]), $matches[2], $matches[3]);

                /* Set Team and Squad references if ListSquads output is provided */
                if ($teams && count($teams) && $matches[4] !== 'N/A' && array_key_exists($teams, $matches[4])) {
                    /* Get the Team */
                    $player->setTeam($teams[$matches[4]]);

                    if (count($player->getTeam()->getSquads()) && $matches[5] !== 'N/A' && array_key_exists($matches[5], $player->getTeam()->getSquads())) {
                        /* Get the Squad */
                        $squad = $player->getTeam()->getSquads()[$matches[5]];

                        /* Add the Player to the Squad */
                        $squad->addPlayer($player);
                    } else {
                        /* Add as unassigned Player to the Team instance */
                        $player->getTeam()->addPlayer($player);
                    }
                }

                /* Add to the output */
                $players[] = $player;
            } else if (preg_match('/^[-]{5} Recently Disconnected Players/', $line)) {
                /* Notihing of interest, break the loop */
                break;
            }
        }

        return $players;
    }

    /**
     * Gets the current map using the ShowNextMap command.
     * 
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function currentMap() : string
    {
        return $this->currentMaps()['current'];
    }

    /**
     * Gets the current next map using the ShowNextMap command.
     * 
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function nextMap() : string
    {
        return $this->currentMaps()['next'];
    }

    /**
     * ShowNextMap command.
     * Gets the current and next map.
     * 
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function currentMaps() : array
    {
        $maps = [
            'current' => null,
            'next' => null
        ];
        $res = $this->_sendCommand("ShowNextMap");
        $arr = explode(', Next map is ', $res);
        if (count($arr) > 1) {
            $next = trim($arr[1]);
            $curr = substr($arr[0], strlen('Current map is '));
            $maps['current'] = $curr;
            $maps['next'] = $next;
        }
        return $maps;
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
     * Helper method to run Console commands.
     * 
     * @param string $cmd
     * @param string $param
     * @param string $rtn
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function _consoleCommand(string $cmd, string $param, string $expected) : bool
    {
        $ret = $this->_sendCommand($cmd . ' ' . $param);
        return substr($ret, 0, strlen($expected)) == $expected;
    }

    /**
     * Helper method to send a command to the Server over
     * RCon. Reads and returns the response.
     * @param $cmd
     * @return mixed
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function _sendCommand($cmd)
    {
        $res = $this->rcon->execute($cmd);
        return $res;
    }
}
