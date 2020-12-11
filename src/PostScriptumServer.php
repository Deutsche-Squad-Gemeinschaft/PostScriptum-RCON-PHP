<?php

namespace DSG\PostScriptumRCON;

use DSG\PostScriptumRCON\Contracts\ServerCommandRunner;
use DSG\PostScriptumRCON\Data\Team;
use DSG\PostScriptumRCON\Data\Squad;
use DSG\PostScriptumRCON\Data\Player;
use DSG\PostScriptumRCON\Data\Population;
use DSG\PostScriptumRCON\Data\ServerConnectionInfo;
use DSG\PostScriptumRCON\Runners\PostScriptumRconRunner;

class PostScriptumServer
{
    const SQUAD_SOCKET_TIMEOUT_SECONDS = 0.5;

    /** @var ServerCommandRunner */
    private ServerCommandRunner $runner;

    /**
     * SquadServer constructor.
     * @param $host
     * @param $port
     * @param $password
     * @param float $timeout
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function __construct(ServerConnectionInfo $serverConnectionInfo, ServerCommandRunner $runner = null)
    {
        /* Initialize the default Runner if none is specified */
        if (!$runner) {
            $runner = new PostScriptumRconRunner($serverConnectionInfo);
        }

        $this->runner = $runner;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function disconnect() : void
    {
        $this->runner->disconnect();
    }

    /**
     * @return Team[]
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function serverPopulation() : Population
    {
        /* Get the current Teams and their Squads */
        $population = new Population($this->listSquads());

        /* Get the currently connected players, feed listSquads output to reference Teams/Squads */
        $this->listPlayers($population);

        return $population;
    }

    /**
     * ListSquads command. Returns an array
     * of Teams containing Squads. The output
     * can be given to the listPlayers method
     * to add and reference the Player instances.
     *
     * @return Team[]
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function listSquads() : array
    {
        /** @var Team[] $teams */
        $teams = [];

        /** @var Squad[] $squads */
        $squads = [];

        /* Get the SquadList from the Server */
        $response = $this->runner->listSquads();

        /** @var Team The current team */
        $currentTeam = null;
        foreach (explode("\n", $response) as $lineSquad) {
            $matches = [];
            if (preg_match('/^Team ID: ([1|2]) \((.*)\)/', $lineSquad, $matches) > 0) {
                /* Initialize a new Team */
                $team = new Team(intval($matches[1]), $matches[2]);

                /* Add to the lookup */
                $teams[$team->getId()] = $team;
                
                /* Initialize squad lookup array */
                $squads[$team->getId()] = [];

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
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function listPlayers(Population &$population = null) : array
    {
        /* Initialize an empty output array */
        $players = [];

        /* Execute the ListPlayers command and get the response */
        $response = $this->runner->listPlayers();

        /* Process each individual line */
        foreach (explode("\n", $response) as $line) {
            /* Initialize an empty array and try to get info form line */
            $matches = [];
            if (preg_match('/^ID: (\d{1,}) \| SteamID: (\d{17}) \| Name: (.*?) \| Team ID: (1|2|N\/A) \| Squad ID: (\d{1,}|N\/A)/', $line, $matches)) {
                /* Initialize new Player instance */
                $player = new Player(intval($matches[1]), $matches[2], $matches[3]);

                /* Set Team and Squad references if ListSquads output is provided */
                if ($population && $population->hasTeams() && $matches[4] !== 'N/A' && $population->getTeam($matches[4])) {
                    /* Get the Team */
                    $player->setTeam($population->getTeam($matches[4]));

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
            } else if (preg_match('/^-{5} Recently Disconnected Players \[Max of 15\] -{5}/', $line)) {
                /* Notihing of interest, break the loop */
                break;
            }
        }

        return $players;
    }

    /**
     * ListDisconnectedPlayers command, returns an array
     * of disconnected Player instances.
     *
     * @return Player[]
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function listDisconnectedPlayers() : array
    {
        /* Initialize an empty output array */
        $players = [];

        /* Execute the ListPlayers command and get the response */
        $response = $this->runner->listPlayers();

        /* Process each individual line */
        foreach (explode("\n", $response) as $line) {
            /* Initialize an empty array and try to get info form line */
            $matches = [];
            if (preg_match('/^ID: (\d{1,}) \| SteamID: (\d{17}) \| Since Disconnect: (\d{2,})m.(\d{2})s \| Name: (.*?)$/', $line, $matches)) {
                /* Initialize new Player instance */
                $player = new Player(intval($matches[1]), $matches[2], $matches[5]);

                /* Set the disconnected since time */
                $player->setDisconnectedSince(intval($matches[3]) * 60 + intval($matches[4]));

                /* Add to the output */
                $players[] = $player;
            }
        }

        return $players;
    }

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminKick(string $nameOrSteamId, string $reason = '') : bool
    {
        return $this->runner->adminKick($nameOrSteamId, $reason);
    }

    /**
     * AdminKickById command.
     * Broadcasts the given message on the server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminKickById(int $id, string $reason = '') : bool
    {
        return $this->runner->adminKickById($id, $reason);
    }

    /**
     * AdminBan command.
     * Bans the given Player from the Server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminBan(string $nameOrSteamId, string $duration = '1d', string $reason = '') : bool
    {
        return $this->runner->adminBan($nameOrSteamId, $duration, $reason);
    }

    /**
     * AdminBanById command.
     * Bans the given Player from the Server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminBanById(int $id, string $duration = '1d', string $reason = '') : bool
    {
        return $this->runner->adminBanById($id, $duration, $reason);
    }

    /**
     * Gets the current map using the ShowNextMap command.
     * 
     * @return string
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function currentMap() : string
    {
        return $this->currentMaps()['current'];
    }

    /**
     * Gets the current next map using the ShowNextMap command.
     * 
     * @return string
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
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
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    private function currentMaps() : array
    {
        /* Initialize the output */
        $maps = [
            'current' => null,
            'next' => null
        ];

        /* Run the ShowNextMap Command and get response */
        $response = $this->runner->showNextMap();

        /* Parse response */
        $arr = explode(', Next map is ', $response);
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
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminBroadcast(string $msg) : bool
    {
        return $this->runner->adminBroadcast($msg);
    }

    /**
     * AdminRestartMatch command.
     * Restarts the current match.
     *
     * @return boolean
     */
    public function adminRestartMatch() : bool
    {
        return $this->runner->adminRestartMatch();
    }

    /**
     * AdminEndMatch command.
     * Ends the current Match.
     *
     * @return boolean
     */
    public function adminEndMatch() : bool
    {
        return $this->runner->adminEndMatch();
    }

    /**
     * AdminSetMaxNumPlayers command.
     * Sets the max amount of players (public).
     *
     * @param int $slots How many public slots ther should be.
     * @return boolean
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminSetMaxNumPlayers(int $slots = 78) : bool
    {
        return $this->runner->adminSetMaxNumPlayers($slots);
    }

    /**
     * AdminSetServerPassword command.
     * Sets the password of the server.
     *
     * @param string $password
     * @return boolean
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminSetServerPassword(string $password) : bool
    {
        return $this->runner->adminSetServerPassword($password);
    }

    /**
     * AdminChangeMap command
     * Immediately changes the current map to the given map.
     * @param string $map
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminChangeMap(string $map) : bool
    {
        return $this->runner->adminChangeMap($map);
    }

    /**
     * AdminSetNextMap command.
     * Temporarily overwrites the next map in the
     * MapRotations, effecively changing the next map.
     * 
     * @param string $map
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminSetNextMap(string $map) : bool
    {
        return $this->runner->adminSetNextMap($map);
    }

    /**
     * AdminSlomo command.
     * Sets the game speed with the AdminSlomo
     * command. Providing no parameter will set
     * the speed to default.
     *
     * @param float $timeDilation
     * @return boolean
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminSlomo(float $timeDilation = 1.0) : bool
    {
        return $this->runner->adminSlomo($timeDilation);
    }

    /**
     * AdminForceTeamChange command.
     * Forces a player to the opposite team
     * by providing the name or steamid.
     *
     * @param string $nameOrSteamId
     * @return boolean
     */
    public function adminForceTeamChange(string $nameOrSteamId) : bool
    {
        return $this->runner->adminForceTeamChange($nameOrSteamId);
    }

    /**
     * AdminForceTeamChangeById command.
     * Forces a player to the opposite team
     * by providing the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    public function adminForceTeamChangeById(int $playerId) : bool
    {
        return $this->runner->adminForceTeamChangeById($playerId);
    }

    /**
     * AdminDemoteCommander command.
     * Demotes a player from the commander slot
     * by providing the name or steamid.
     *
     * @param string $nameOrSteamId
     * @return boolean
     */
    //public function adminDemoteCommander(string $nameOrSteamId) : bool
    //{
    //    return $this->runner->adminDemoteCommander($nameOrSteamId);
    //}

    /**
     * AdminDemoteCommanderById command.
     * Demotes a player from the commander slot
     * by providing the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    //public function adminDemoteCommanderById(int $playerId) : bool
    //{
    //    return $this->runner->adminDemoteCommanderById($playerId);
    //}

    /**
     * AdminDisbandSquad command.
     * Disbands a Squad by providing the Team id  / index & Squad id / index.
     *
     * @param integer $teamId
     * @param integer $squadId
     * @return boolean
     */
    public function adminDisbandSquad(int $teamId, int $squadId) : bool
    {
        return $this->runner->adminDisbandSquad($teamId, $squadId);
    }

    /**
     * AdminRemovePlayerFromSquad command.
     * Removes a Player from his Squad by providing
     * the Player name.
     *
     * @param string $playerName
     * @return boolean
     */
    public function adminRemovePlayerFromSquad(string $playerName) : bool
    {
        return $this->runner->adminRemovePlayerFromSquad($playerName);
    }

    /**
     * AdminRemovePlayerFromSquadById command.
     * Removes a player from his Squad by providing
     * the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    public function adminRemovePlayerFromSquadById(int $playerId) : bool
    {
        return $this->runner->adminRemovePlayerFromSquadById($playerId);
    }

    /**
     * AdminWarn command.
     * Warns a Player by providing his name / steamid
     * and a message.
     *
     * @param string $nameOrSteamId
     * @param string $warnReason
     * @return boolean
     */
    public function adminWarn(string $nameOrSteamId, string $warnReason) : bool
    {
        return $this->runner->adminWarn($nameOrSteamId, $warnReason);
    }

    /**
     * AdminWarnById command.
     * Warns a Player by providing his ingame Player id
     * and a message.
     *
     * @param integer $playerId
     * @param string $warnReason
     * @return boolean
     */
    public function adminWarnById(int $playerId, string $warnReason) : bool
    {
        return $this->runner->adminWarnById($playerId, $warnReason);
    }
}