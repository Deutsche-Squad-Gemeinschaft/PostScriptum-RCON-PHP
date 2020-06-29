<?php

namespace DSG\SquadRCON\RCon;

use DSG\SquadRCON\Exceptions\RConException;

/**
 * Squad RCon implementation by 
 * https://github.com/Brozowski
 */
class BrozowskiSquadRcon
{
    const SERVERDATA_EXECCOMMAND = 2;
    const SERVERDATA_AUTH = 3;
    const SOCKET_TIMEOUT_SECONDS = 2.5;

    /**
     * Host of the RCon endpoint.
     */
    private string $host;

    /**
     * Port of the RCon endpoint.
     */
    private int $port = 27015;

    /**
     * The password for the RCon connection.
     */
    private string $passsword;

    /**
     * The socket of this RCon client
     *
     * @var resource
     */
    private $socket;

    /**
     * Determines if the RCon client is authenticated.
     */
    private bool $isAuthenticated = false;

    /**
     * The command id
     */
    private int $id = 0;

    /**
     * @throws RConException
     */
    public function __construct(string $host, int $port, string $password, float $timeout = self::SOCKET_TIMEOUT_SECONDS)
    {
        $this->passsword = $password;

        /* Connect to the Server */
        $this->socket = @fsockopen($host, $port, $errno, $errstr, 30);
        if ($this->socket === false) {
            throw new RConException("Unable to open socket: $errstr ($errno)");
        }

        /* Set the timeout */
        $secs = intval($timeout);
        $milis = is_float($timeout) ? ($timeout - $secs) * 1000000 : 0;
        stream_set_timeout($this->socket, $secs, $milis);
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Closes and disconnects the current socket.
     *
     * @return void
     */
    public function disconnect() : void
    {
        if ($this->socket) {
            if (is_resource($this->socket)) {
                fclose($this->socket);
            }
            $this->socket = null;
        }
    }

    /**
     * Executes a RCon command.
     * 
     * @return mixed
     * @throws RConException
     */
    public function rcon(string $command)
    {
        $this->sendCommand($command);
        $ret = $this->read();
        if ($ret === null) {
            throw new RConException("Bad response from server");
        }
        return $ret[$this->id]['S1'];
    }

    /**
     * Authenticates the client to the Server.
     * 
     * @throws RConException
     */
    private function authenticate() : void
    {
        if (!$this->isAuthenticated) {
            /* Write the auth packet with the rcon password */
            $this->write(self::SERVERDATA_AUTH, $this->passsword);

            /* Read the response from the Server */
            $ret = $this->packetRead();

            /* Check if we sucessfully authenticated */
            if (isset($ret[1]['id']) && $ret[1]['id'] == -1) {
                throw new RConException("Authentication Failure");
            }

            /* Set status to authenticated to prevent re-authentication */
            $this->isAuthenticated = true;
        }
    }

    /**
     * Sends a command to the server, can sanitize it.
     * 
     * @throws RConException
     */
    private function sendCommand(string $command, bool $sanitize = false) : void
    {
        $this->authenticate();

        if ($sanitize) {
            $command = '"' . trim(str_replace(' ', '" "', $command)) . '"';
        }
            
        $this->write(self::SERVERDATA_EXECCOMMAND, $command, '');
    }

    /**
     * Increases the ID and writes the command to the server.
     */
    private function write(string $cmd, string $s1 = '', string $s2 = '') : int
    {
        $id = ++$this->id;
        $data = pack("VV", $id, $cmd) . $s1 . chr(0) . $s2 . chr(0);
        $data = pack("V", strlen($data)) . $data;
        fwrite($this->socket, $data, strlen($data));
        return $id;
    }

    /**
     * Reads received packages from the server.
     */
    private function read() : ?array
    {
        /** @var array $ret */
        $ret = [];

        $packets = $this->packetRead();

        foreach ($packets as $pack) {
            if (isset($ret[$pack['ID']])) {
                $ret[$pack['ID']]['S1'] .= $pack['S1'];
                $ret[$pack['ID']]['S2'] .= $pack['S1'];
            } else {
                $ret[$pack['ID']] = array(
                    'Response' => $pack['Response'],
                    'S1' => $pack['S1'],
                    'S2' => $pack['S2'],
                );
            }
        }

        if (isset($ret)) {
            return $ret;
        }

        return null;
    }

    /**
     * Reads a packet from the socket.
     */
    private function packetRead() : array
    {
        /* Initialize an empty output array */
        $retarray = [];

        /* Read socket */
        while ($read = @fread($this->socket, 4)) {
            /* Get the size */
            $size = unpack('V1Size', $read);

            /* Initialize an empty packet */
            $packet = null;

            /* Add prefix if the maximum RCON packet size is exceeded */
            if ($size["Size"] > 4096) {
                $packet = "\x00\x00\x00\x00\x00\x00\x00\x00" . fread($this->socket, 4096);
            } else {
                $packet = fread($this->socket, $size["Size"]);
            }
            
            /* Add packet to the result set (if there is one) */
            if (!is_null($packet)) {
                array_push($retarray, unpack("V1ID/V1Response/a*S1/a*S2", $packet));
            }
        }

        return $retarray;
    }
}