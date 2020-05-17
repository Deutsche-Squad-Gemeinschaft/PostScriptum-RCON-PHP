<?php

namespace DSG\SquadRCON\Tests\Runners\Responses;

class ListSquadsResponse {
    public static function get() {
        return <<<EOT
----- Active Squads -----
Team ID: 1 (United States Army)
ID: 1 | Name: HELI | Size: 1 | Locked: True
ID: 2 | Name: HELI | Size: 1 | Locked: True
ID: 3 | Name: CMD Squad | Size: 9 | Locked: False
ID: 4 | Name: MBT | Size: 5 | Locked: True
ID: 5 | Name: BRADLEY | Size: 2 | Locked: True
ID: 6 | Name: STRYKER | Size: 2 | Locked: False
ID: 7 | Name: BOS SACHEN MACHEN | Size: 9 | Locked: False
ID: 8 | Name: RUNNING SQUAD | Size: 8 | Locked: False
Team ID: 2 (Russian Ground Forces)
ID: 1 | Name: STURMTRUPP | Size: 6 | Locked: True
ID: 2 | Name: CMD Squad | Size: 9 | Locked: False
ID: 3 | Name: LOGI GER | Size: 1 | Locked: True
ID: 4 | Name: BMP GER | Size: 2 | Locked: True
ID: 5 | Name: GER MIC | Size: 8 | Locked: False
ID: 6 | Name: (DE) HELI 1 | Size: 1 | Locked: True
ID: 7 | Name: MBT | Size: 2 | Locked: True
ID: 8 | Name: CHOPPA | Size: 3 | Locked: True
ID: 9 | Name: SCOUT CAR | Size: 1 | Locked: True
ID: 10 | Name: GER INF | Size: 6 | Locked: False
EOT;
    }
}