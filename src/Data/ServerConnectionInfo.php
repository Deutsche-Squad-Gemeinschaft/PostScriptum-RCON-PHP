<?php

namespace DSG\SquadRCON\Data;

class ServerConnectionInfo {
    const SQUAD_SOCKET_TIMEOUT_SECONDS = 0.5;

    /**
     * Host of the Server.
     * 
     * @var string
     */
    public string $host;

    /**
     * (RCon) Port of the Server.
     * 
     * @var int
     */
    public int $port;

    /**
     * (RCon) Password of the Server.
     * 
     * @var string
     */
    public string $password;

    /**
     * Timeout for the RCon connection.
     * 
     * @var int
     */
    public int $timeout;

    function __construct(string $host, int $port, string $password, int $timeout = ServerConnectionInfo::SQUAD_SOCKET_TIMEOUT_SECONDS)
    {
        $this->host     = $host;
        $this->port     = $port;
        $this->password = $password;
        $this->timeout  = $timeout;
    }
}