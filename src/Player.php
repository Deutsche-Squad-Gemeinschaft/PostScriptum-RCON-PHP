<?php


namespace SquadSlovenia;


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
    private $team;

    /**
     * @var Squad
     */
    private $squad;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSteamId()
    {
        return $this->steamId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @return Squad
     */
    public function getSquad()
    {
        return $this->squad;
    }

}