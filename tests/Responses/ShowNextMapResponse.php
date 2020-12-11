<?php

namespace DSG\PostScriptumRCON\Tests\Runners\Responses;

class ShowNextMapResponse {
    public static function get() {
        return <<<EOT
Current map is Al Basrah AAS v1, Next map is Belaya AAS v1
EOT;
    }
}