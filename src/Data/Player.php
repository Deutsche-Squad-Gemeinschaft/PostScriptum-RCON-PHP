<?php

namespace DSG\SquadRCON\Data;

class Player
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $steamId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Team
     */
    private $team = null;

    /**
     * @var Squad
     */
    private $squad = null;

    function __construct(int $id, string $steamId, string $name)
    {
        $this->id       = $id;
        $this->steamId  = $steamId;
        $this->name     = $name;
    }

    /**
     * Get the ID of this Player instance.
     * 
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the SteamId of this Player instance.
     * 
     * @return string
     */
    public function getSteamId() : string
    {
        return $this->steamId;
    }

    /**
     * Get the name of this Player instance.
     * 
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the Team this player instance is assigned to.
     * 
     * @return Team|null
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Sets the Team of this Player instance
     *
     * @param Team $team
     * @return void
     */
    public function setTeam(Team $team) : void
    {
        $this->team = $team;
    }

    /**
     * Get the Squad this Player instance is assigned to.
     * 
     * @return Squad|null
     */
    public function getSquad()
    {
        return $this->squad;
    }

    /**
     * Sets the Squad of this Player instance
     *
     * @param Squad $squad
     * @return void
     */
    public function setSquad(Squad $squad) : void
    {
        $this->squad = $squad;
    }
}