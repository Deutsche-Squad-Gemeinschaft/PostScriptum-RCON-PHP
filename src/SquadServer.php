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

        $resPlayers = $this->rcon->execute("ListPlayers");
        $linesPlayers = explode("\n", $resPlayers);
        foreach ($linesPlayers as $linePlayer) {
            $matches = [];
            if (preg_match('/^ID: (\d{1,}) \| SteamID: (\d{17}) \| Name: (.*?) \| Team ID: (1|2|N\/A) \| Squad ID: (\d{1,}|N\/A)/', $linePlayer, $matches)) {
                
                $squadId = $matches[5];

                $player = new Player(intval($matches[1]), $matches[2], $matches[3]);

                /* Get the players team and reference */
                $teamId = $matches[4];
                if (array_key_exists($teamId, $teams)) {
                    $player->setTeam($teams[$teamId]);
                }

                /* Get and add to squad, else add to team */
                if ($player->getTeam()) {
                    if ($squadId !== 'N/A' && array_key_exists($squadId, $squads[$player->getTeam()->getId()])) {
                        /* Get reference of the Squad */
                        $squad = $squads[$player->getTeam()->getId()][$squadId];

                        /* Add the Player to the Squad */
                        $squad->addPlayer($player);
                    } else {
                        /* Add the Player to the Team */
                        $player->getTeam()->addPlayer($player);
                    }
                }
            } else if (preg_match('/^[-]{5} Recently Disconnected Players/', $linePlayer)) {
                break;
            }
        }

        return $teams;
    }

    /**
     * @param array $ignored
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     * @deprecated
     */
    public function currentPlayers($ignored = []) : array
    {
        $res = $this->rcon->execute("ListPlayers");
        $ra = explode("\n", $res);
        $players = [];
        for ($i = 1; $i < count($ra); $i++) {
            /* Get the current line */
            $l = trim($ra[$i]);

            /* Check if we already reached the end */
            if ($l == '----- Recently Disconnected Players [Max of 15] -----') {
                break;
            }

            /* Skip empty or malformed results */
            if (empty($l) || !preg_match('/ID:\s\d*\s\|\sSteamID:\s\d*\s\|\sName:\s.*\|\sTeam\sID:\s\d\s\|\sSquad\sID:\s\d*/', $l)) {
                continue;
            }

            $pla = explode(' | ', $l);
            $pli = substr($pla[0], 4);
            $pls = substr($pla[1], 9);
            $pln = substr($pla[2], 6);
            if (!in_array($pls, $ignored)) {
                $players[] = array(
                    'id' => $pli,
                    'steam_id' => $pls,
                    'name' => $pln,
                );
            }
        }
        return $players;
    }

    /**
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function currentMap() : string
    {
        return $this->currentMaps()['current'];
    }

    /**
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function currentNext() : string
    {
        return $this->currentMaps()['next'];
    }

    /**
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     * @deprecated
     */
    public function currentMaps() : array
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
     * @param $msg
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function broadcastMessage($msg) : bool
    {
        return $this->_consoleCommand('AdminBroadcast', $msg, 'Message broadcasted');
    }

    /**
     * @param $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function changeMap($map) : bool
    {
        return $this->_consoleCommand('AdminChangeMap', $map, 'Changed map to');
    }

    /**
     * @param $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function nextMap($map) : bool
    {
        return $this->_consoleCommand('AdminSetNextMap', $map, 'Set next map to');
    }

    /**
     * @param $cmd
     * @param $param
     * @param $rtn
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function _consoleCommand($cmd, $param, $rtn) : bool
    {
        $ret = $this->_sendCommand($cmd . ' ' . $param);
        return substr($ret, 0, strlen($rtn)) == $rtn;
    }

    /**
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
