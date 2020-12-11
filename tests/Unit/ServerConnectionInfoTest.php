<?php

namespace DSG\PostScriptumRCON\Tests\Unit;

use DSG\PostScriptumRCON\Data\ServerConnectionInfo;

class ServerConnectionInfoTest extends \DSG\PostScriptumRCON\Tests\TestCase {
    /**
     * Validates the ServerConnectionInfo can be initialized
     * 
     * @return void
     */
    public function test_server_connection_info_can_be_initialized()
    {
        $info = new ServerConnectionInfo('localhost', 12345, 'secret');
        
        $this->assertTrue((bool)$info);
    }

    /**
     * Validates that the provided timeout is validated.
     * 
     * @return void
     */
    public function test_server_connection_info_timeout_being_validated()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        new ServerConnectionInfo('localhost', 12345, 'secret', 0);
    }
}