<?php

namespace DSG\PostScriptumRCON\Contracts;

interface ServerCommandRunner {
    /**
     * ListSquads command. Returns an array
     * of Teams containing Squads. The output
     * can be given to the listPlayers method
     * to add and reference the Player instances.
     *
     * @return Team[]
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function listSquads() : string;

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
    public function listPlayers() : string;

    /**
     * ListDisconnectedPlayers command, returns an array
     * of disconnected Player instances.
     *
     * @return Player[]
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function listDisconnectedPlayers() : string;

    /**
     * AdmiNkick command.
     * Kick a Player by Name or Steam64ID
     * 
     * @param string $nameOrSteamId
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminKick(string $nameOrSteamId, string $reason = '') : bool;

    /**
     * AdminKickById command.
     * Broadcasts the given message on the server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminKickById(int $id, string $reason = '') : bool;

    /**
     * AdminBan command.
     * Bans the given Player from the Server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminBan(string $nameOrSteamId, string $duration = '1d', string $reason = '') : bool;

    /**
     * AdminBanById command.
     * Bans the given Player from the Server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminBanById(int $id, string $duration = '1d', string $reason = '') : bool;

    /**
     * ShowNextMap command.
     * Gets the current and next map.
     * 
     * @return array
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function showNextMap() : string;

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminBroadcast(string $msg) : bool;

    /**
     * ChatToAdmin command.
     * Restarts the current match.
     *
     * @return boolean
     */
    public function adminRestartMatch() : bool;

    /**
     * AdminEndMatch command.
     * Ends the current Match.
     *
     * @return boolean
     */
    public function adminEndMatch() : bool;

    /**
     * AdminSetMaxNumPlayers command.
     * Sets the max amount of players (public).
     *
     * @param int $slots How many public slots ther should be.
     * @return boolean
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminSetMaxNumPlayers(int $slots) : bool;

    /**
     * AdminSetServerPassword command.
     * Sets the password of the server.
     *
     * @param string $password
     * @return boolean
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminSetServerPassword(string $password) : bool;

    /**
     * AdminChangeMap command
     * Immediately changes the current map to the given map.
     * @param string $map
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminChangeMap(string $map) : bool;

    /**
     * AdminSetNextMap command.
     * Temporarily overwrites the next map in the
     * MapRotations, effecively changing the next map.
     * 
     * @param string $map
     * @return bool
     * @throws \DSG\PostScriptumRCON\Exceptions\RConException
     */
    public function adminSetNextMap(string $map) : bool;

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
    public function adminSlomo(float $timeDilation = 1.0) : bool;

    /**
     * AdminForceTeamChange command.
     * Forces a player to the opposite team
     * by providing the name or steamid.
     *
     * @param string $nameOrSteamId
     * @return boolean
     */
    public function adminForceTeamChange(string $nameOrSteamId) : bool;

    /**
     * AdminForceTeamChangeById command.
     * Forces a player to the opposite team
     * by providing the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    public function adminForceTeamChangeById(int $playerId) : bool;

    /**
     * AdminDemoteCommander command.
     * Demotes a player from the commander slot
     * by providing the name or steamid.
     *
     * @param string $nameOrSteamId
     * @return boolean
     */
    //public function adminDemoteCommander(string $nameOrSteamId) : bool;

    /**
     * AdminDemoteCommanderById command.
     * Demotes a player from the commander slot
     * by providing the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    //public function adminDemoteCommanderById(int $playerId) : bool;

    /**
     * AdminDisbandSquad command.
     * Disbands a Squad by providing the Team id  / index & Squad id / index.
     *
     * @param integer $teamId
     * @param integer $squadId
     * @return boolean
     */
    public function adminDisbandSquad(int $teamId, int $squadId) : bool;

    /**
     * AdminRemovePlayerFromSquad command.
     * Removes a Player from his Squad by providing
     * the Player name.
     *
     * @param string $playerName
     * @return boolean
     */
    public function adminRemovePlayerFromSquad(string $playerName) : bool;

    /**
     * AdminRemovePlayerFromSquadById command.
     * Removes a player from his Squad by providing
     * the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    public function adminRemovePlayerFromSquadById(int $playerId) : bool;

    /**
     * AdminWarn command.
     * Warns a Player by providing his name / steamid
     * and a message.
     *
     * @param string $nameOrSteamId
     * @param string $warnReason
     * @return boolean
     */
    public function adminWarn(string $nameOrSteamId, string $warnReason) : bool;

    /**
     * AdminWarnById command.
     * Warns a Player by providing his ingame Player id
     * and a message.
     *
     * @param integer $playerId
     * @param string $warnReason
     * @return boolean
     */
    public function adminWarnById(int $playerId, string $warnReason) : bool;

    /**
     * Disconnects the runner from any squad server instance.
     *
     * @return void
     */
    public function disconnect() : void;
}