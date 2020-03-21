# RCON PHP wrapper for Squad server management

[![Total Downloads](https://poser.pugx.org/dsg/squad-rcon-php/downloads.png)](https://packagist.org/dsg/squad-rcon-php/shoppingcart)
[![Latest Stable Version](https://poser.pugx.org/dsg/squad-rcon-php/v/stable)](https://packagist.org/packages/dsg/squad-rcon-php)
[![Latest Unstable Version](https://poser.pugx.org/dsg/squad-rcon-php/v/unstable)](https://packagist.org/packages/dsg/squad-rcon-php)
[![License](https://poser.pugx.org/dsg/squad-rcon-php/license)](https://packagist.org/packages/dsg/squad-rcon-php)

## USAGE

### Create instance
$server = new SquadServer('server.squad-slovenia.com', 21114, 'verySecretPassword');

### Get currently active players
$players = $server->currentPlayers();

### Get next and current map
$maps = $server->currentMaps();

### Broadcast message to all players on the server
$server->broadcastMessage('Hello from the other side');

### Change map (end current game)
$server->changeMap('Sumari AAS v1');

### Set next map
$server->nextMap('Gorodok');


## AVAILABLE COMMANDS (NOV 2016)

* ListPlayers
* ShowNextMap
* AdminKick "\<NameOrSteamId\>" \<KickReason\>
* AdminKickById \<PlayerId\> \<KickReason\>
* AdminBan "\<NameOrSteamId\>" "\<BanLength\>" \<BanReason\>
* AdminBanById \<PlayerId\> "\<BanLength\>" \<BanReason\>
* AdminBroadcast \<Message\>
* ChatToAdmin \<Message\>
* AdminRestartMatch
* AdminEndMatch
* AdminPauseMatch
* AdminUnpauseMatch
* AdminKillServer \<Force [0|1]\>
* AdminChangeMap \<MapName\>
* AdminSetNextMap \<MapName\>
* AdminSetMaxNumPlayers \<NumPlayers\>
* AdminSetNumReservedSlots \<NumReserved\>
* AdminSetServerPassword \<Password\>
* AdminAddCameraman \<NameOrId\>
* AdminDemoRec \<FileName\>
* AdminDemoStop
* AdminListDisconnectedPlayers
* AdminForceNetUpdateOnClientSaturation \<Enabled [0|1]\>
