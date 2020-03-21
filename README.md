<div align="center">
    <h1>Squad RCON PHP</h1>
    <b>RCON PHP wrapper for Squad server management</b>
    <hr>
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
* [x] ShowNextMap
* [x] AdminKick "\<NameOrSteamId\>" \<KickReason\>
* [x] AdminKickById \<PlayerId\> \<KickReason\>
* [x] AdminBan "\<NameOrSteamId\>" "\<BanLength\>" \<BanReason\>
* [x] AdminBanById \<PlayerId\> "\<BanLength\>" \<BanReason\>
* [x] AdminBroadcast \<Message\>
* [x] ChatToAdmin \<Message\>
* [x] AdminRestartMatch
* [x] AdminEndMatch
* [x] AdminPauseMatch
* [x] AdminUnpauseMatch
* [x] AdminKillServer \<Force [0|1]\>
* [x] AdminChangeMap \<MapName\>
* [x] AdminSetNextMap \<MapName\>
* [x] AdminSetMaxNumPlayers \<NumPlayers\>
* [x] AdminSetNumReservedSlots \<NumReserved\>
* [x] AdminSetServerPassword \<Password\>
* [x] AdminAddCameraman \<NameOrId\>
* [x] AdminDemoRec \<FileName\>
* [x] AdminDemoStop
* [x] AdminListDisconnectedPlayers
* [x] AdminForceNetUpdateOnClientSaturation \<Enabled [0|1]\>

## USAGE

### Create instance
```php
$server = new SquadServer('server.squad-slovenia.com', 21114, 'verySecretPassword');
```

### Get currently active players
```php
$players = $server->currentPlayers();
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
$server->nextMap('Gorodok');
```