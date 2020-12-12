<?php

namespace DSG\PostScriptumRCON\Tests\Runners\Responses;

class ShowNextMapResponse {
    public static function get() {
        return <<<EOT
Current map is Heelsum Single 01, Next map is Driel Single 01
EOT;
    }
}