<div align="center">
    <h1>Squad RCON PHP</h1>
    <b>RCON PHP wrapper for Squad server management</b>
    <hr>
    <a href="https://dsg-gaming.de">
        <img alt="Deutsche Squad Gemeinschaft" src="https://raw.githubusercontent.com/Deutsche-Squad-Gemeinschaft/battlemetrics-php/master/dsg-badge.svg">
    </a>
    <a href="https://travis-ci.org/Deutsche-Squad-Gemeinschaft/squad-rcon-php">
        <img alt="Deutsche Squad Gemeinschaft" src="https://travis-ci.org/Deutsche-Squad-Gemeinschaft/squad-rcon-php.png?branch=master">
    </a>
    <a href="https://codecov.io/gh/Deutsche-Squad-Gemeinschaft/squad-rcon-php">
        <img alt="Deutsche Squad Gemeinschaft" src="https://codecov.io/gh/Deutsche-Squad-Gemeinschaft/squad-rcon-php/branch/master/graph/badge.svg">
    </a>
    <a href="https://packagist.org/dsg/squad-rcon-php/shoppingcart">
        <img alt="Total Downloads" src="https://poser.pugx.org/dsg/squad-rcon-php/downloads.png">
    </a>
    <a href="https://packagist.org/packages/dsg/squad-rcon-php">
        <img alt="Latest Stable Version" src="https://poser.pugx.org/dsg/squad-rcon-php/v/stable">
    </a>
    <a href="https://packagist.org/packages/dsg/squad-rcon-php">
        <img alt="Latest Unstable Version" src="https://poser.pugx.org/dsg/squad-rcon-php/v/unstable">
    </a>
    <a href="https://packagist.org/packages/dsg/squad-rcon-php">
        <img alt="License" src="https://poser.pugx.org/dsg/squad-rcon-php/license">
    </a>
</div>

## Installation

You can install this package by using composer and the following command:
```
composer require dsg/squad-rcon-php
```

The code will then be available under the `DSG\SquadRCON` namespace.

## Commands

* [x] ListPlayers
* [x] ListSquads
* [x] ShowNextMap
* [ ] AdminKick "\<NameOrSteamId\>" \<KickReason\>
* [ ] AdminKickById \<PlayerId\> \<KickReason\>
* [ ] AdminBan "\<NameOrSteamId\>" "\<BanLength\>" \<BanReason\>
* [ ] AdminBanById \<PlayerId\> "\<BanLength\>" \<BanReason\>
* [x] AdminBroadcast \<Message\>
* [ ] ChatToAdmin \<Message\>
* [ ] AdminRestartMatch
* [ ] AdminEndMatch
* [ ] AdminPauseMatch
* [ ] AdminUnpauseMatch
* [ ] AdminKillServer \<Force [0|1]\>
* [x] AdminChangeMap \<MapName\>
* [x] AdminSetNextMap \<MapName\>
* [ ] AdminSetMaxNumPlayers \<NumPlayers\>
* [ ] AdminSetNumReservedSlots \<NumReserved\>
* [ ] AdminSetServerPassword \<Password\>
* [ ] AdminAddCameraman \<NameOrId\>
* [ ] AdminDemoRec \<FileName\>
* [ ] AdminDemoStop
* [ ] AdminListDisconnectedPlayers
* [ ] AdminForceNetUpdateOnClientSaturation \<Enabled [0|1]\>

## USAGE

### Create instance
```php
$server = new SquadServer(new ServerConnectionInfo('server.squad-slovenia.com', 21114, 'verySecretPassword'));
```

### Get current server population (Teams, Squads, Players)
```php
$players = $server->serverPopulation();
```

### Get currently active players
```php
$players = $server->listPlayers();
```

### Get currently active squads (and teams)
```php
$players = $server->listSquads();
```

### Get next and current map
```php
$maps = $server->currentMaps();
```

### Broadcast message to all players on the server
```php
$server->broadcastMessage('Hello from the other side');
```

### Change map (end current game)
```php
$server->changeMap('Sumari AAS v1');
```

### Set next map
```php
$server->nextMap('Sumari AAS v1');
```