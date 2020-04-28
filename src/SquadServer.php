<?php

namespace DSG\SquadRCON;

class SquadServer
{
    const SQUAD_SOCKET_TIMEOUT_SECONDS = 0.5;

    /** @var RCon */
    private $rcon;

    private static function propSet($object, $prop, $value) : void
    {
        $objClass = new \ReflectionClass($object);
        $property = $objClass->getProperty($prop);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    private static function propAdd($object, $prop, $value) : void
    {
        $objClass = new \ReflectionClass($object);
        $property = $objClass->getProperty($prop);
        $property->setAccessible(true);
        $currentArray = $property->getValue($object);
        if (!is_array($currentArray)) {
            $currentArray = [];
        }
        $currentArray[] = $value;
        $property->setValue($object, $currentArray);
    }

    /**
     * SquadServer constructor.
     * @param $host
     * @param $port
     * @param $password
     * @param float $timeout
     * @throws RConException
     */
    public function __construct($host, $port, $password, $timeout = SquadServer::SQUAD_SOCKET_TIMEOUT_SECONDS)
    {
        $this->rcon = new RCon($host, $port, $password, $timeout);
    }

    /**
     * @return Team[]
     * @throws RConException
     */
    public function serverPopulation() : array
    {
        /** @var Team[] $teams */
        $teams = [];
        /** @var Squad[] $squads */
        $squads = [];
        /** @var Player[] $players */
        $players = [];

        $resSquads = $this->rcon->execute("ListSquads");
        $linesSquads = explode("\n", $resSquads);
        foreach ($linesSquads as $lineSquad) {
            $matches = [];
            if (preg_match('/^Team ID: ([1|2]) \((.*)\)/', $lineSquad, $matches) > 0) {
                $id = intval($matches[1]);
                $name = $matches[2];

                $team = new Team();
                static::propSet($team, 'id', $id);
                static::propSet($team, 'name', $name);
                $teams[$id] = $team;
            } else if (preg_match('/^ID: (\d{1,}) \| Name: (.*?) \| Size: (\d) \| Locked: (True|False)/', $lineSquad, $matches) > 0) {
                $id = intval($matches[1]);
                $name = $matches[2];
                $size = intval($matches[3]);
                $locked = $matches[4] === "True";

                $squad = new Squad();
                static::propSet($squad, 'id', $id);
                static::propSet($squad, 'name', $name);
                static::propSet($squad, 'size', $size);
                static::propSet($squad, 'locked', $locked);
                $squads[$id] = $squad;
            }
        }

        $resPlayers = $this->rcon->execute("ListPlayers");
        $linesPlayers = explode("\n", $resPlayers);
        foreach ($linesPlayers as $linePlayer) {
            $matches = [];
            if (preg_match('/^ID: (\d{1,}) \| SteamID: (\d{17}) \| Name: (.*?) \| Team ID: (1|2|N\/A) \| Squad ID: (\d{1,})/', $linePlayer, $matches)) {
                $id = intval($matches[1]);
                $steamId = $matches[2];
                $name = $matches[3];
                $teamId = $matches[4];
                $squadId = $matches[5];
                $team = array_key_exists($teamId, $teams) ? $teams[$teamId] : null;
                $squad = array_key_exists($squadId, $squads) ? $squads[$squadId] : null;

                $player = new Player();
                static::propSet($player, 'id', $id);
                static::propSet($player, 'steamId', $steamId);
                static::propSet($player, 'name', $name);
                static::propSet($player, 'team', $team);
                static::propSet($player, 'squad', $squad);
                $players[$id] = $player;
            } else if (preg_match('/^[-]{5} Recently Disconnected Players/', $linePlayer)) {
                break;
            }
        }

        foreach ($players as $player) {
            if ($player->getSquad() === null) {
                static::propAdd($player->getTeam(), 'players', $player);
                continue;
            }

            $squad = $player->getSquad();
            if ($squad->getTeam() === null) {
                static::propSet($squad, 'team', $player->getTeam());
                static::propAdd($player->getTeam(), 'squads', $squad);
            }

            static::propAdd($squad, 'players', $player);
        }

        return $teams;
    }

    /**
     * @param array $ignored
     * @return array
     * @throws RConException
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
     * @throws RConException
     */
    public function currentMap() : string
    {
        return $this->currentMaps()['current'];
    }

    /**
     * @return string
     * @throws RConException
     */
    public function currentNext() : string
    {
        return $this->currentMaps()['next'];
    }

    /**
     * @return array
     * @throws RConException
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
     * @throws RConException
     */
    public function broadcastMessage($msg) : bool
    {
        return $this->_consoleCommand('AdminBroadcast', $msg, 'Message broadcasted');
    }

    /**
     * @param $map
     * @return bool
     * @throws RConException
     */
    public function changeMap($map) : bool
    {
        return $this->_consoleCommand('AdminChangeMap', $map, 'Changed map to');
    }

    /**
     * @param $map
     * @return bool
     * @throws RConException
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
     * @throws RConException
     */
    private function _consoleCommand($cmd, $param, $rtn) : bool
    {
        $ret = $this->_sendCommand($cmd . ' ' . $param);
        return substr($ret, 0, strlen($rtn)) == $rtn;
    }

    /**
     * @param $cmd
     * @return mixed
     * @throws RConException
     */
    private function _sendCommand($cmd)
    {
        $res = $this->rcon->execute($cmd);
        return $res;
    }
}
