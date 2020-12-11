<p align="center">
    <img align="center" alt="Squad RCON PHP Logo" src="https://raw.githubusercontent.com/Deutsche-Squad-Gemeinschaft/PostScriptum-RCON-PHP/master/logo.svg">
</p>
<div align="center">
    <h1 align="center">Post Scriptum RCON PHP</h1>
    <p align="center">
        <b>RCON PHP wrapper for Post Scriptum server management</b>
    </p>
    <hr>
    <a href="https://github.com/Deutsche-Squad-Gemeinschaft/PostScriptum-RCON-PHP/actions">
        <img alt="Deutsche Squad Gemeinschaft" src="https://github.com/Deutsche-Squad-Gemeinschaft/PostScriptum-RCON-PHP/workflows/CI/badge.svg">
    </a>
    <a href="https://codecov.io/gh/Deutsche-Squad-Gemeinschaft/PostScriptum-RCON-PHP">
        <img alt="Deutsche Squad Gemeinschaft" src="https://codecov.io/gh/Deutsche-Squad-Gemeinschaft/PostScriptum-RCON-PHP/branch/master/graph/badge.svg">
    </a>
    <a href="https://packagist.org/packages/dsg/postscriptum-rcon-php">
        <img alt="Total Downloads" src="https://poser.pugx.org/dsg/postscriptum-rcon-php/downloads.png">
    </a>
    <a href="https://packagist.org/packages/dsg/postscriptum-rcon-php">
        <img alt="Latest Stable Version" src="https://poser.pugx.org/dsg/postscriptum-rcon-php/v/stable">
    </a>
    <a href="https://packagist.org/packages/dsg/postscriptum-rcon-php">
        <img alt="Latest Unstable Version" src="https://poser.pugx.org/dsg/postscriptum-rcon-php/v/unstable">
    </a>
    <a href="https://packagist.org/packages/dsg/postscriptum-rcon-php">
        <img alt="License" src="https://poser.pugx.org/dsg/postscriptum-rcon-php/license">
    </a>
    <br>
    <a href="https://dsg-gaming.de">
        <img alt="Deutsche Squad Gemeinschaft" src="https://raw.githubusercontent.com/Deutsche-Squad-Gemeinschaft/battlemetrics-php/master/dsg-badge.svg">
    </a>
    <a href="https://discord.gg/9F2Ng5C">
        <img alt="Discord" src="https://img.shields.io/discord/266210223406972928.svg?style=flat-square&logo=discord">
    </a>
</div>

## Installation

You can install this package by using composer and the following command:
```
composer require dsg/postscriptum-rcon-php
```

The code will then be available under the `DSG\PostScriptumRCON` namespace.

## Commands

* [x] ListPlayers
* [x] ListSquads
* [x] AdminListDisconnectedPlayers
* [x] ShowNextMap
* [x] AdminKick "\<NameOrSteamId\>" \<KickReason\>
* [x] AdminKickById \<PlayerId\> \<KickReason\>
* [x] AdminBan "\<NameOrSteamId\>" "\<BanLength\>" \<BanReason\>
* [x] AdminBanById \<PlayerId\> "\<BanLength\>" \<BanReason\>
* [x] AdminBroadcast \<Message\>
* [x] AdminRestartMatch
* [x] AdminEndMatch
* [x] AdminChangeMap \<MapName\>
* [x] AdminSetNextMap \<MapName\>
* [x] AdminSetMaxNumPlayers \<NumPlayers\>
* [x] AdminSetServerPassword \<Password\>
* [x] AdminForceTeamChange \<NameOrSteamId\>
* [x] AdminForceTeamChangeById \<PlayerId\> 
* [ ] AdminDemoteCommander \<PlayerName\>
* [ ] AdminDemoteCommanderById \<PlayerId\>
* [x] AdminDisbandSquad \<TeamId\> \<SquadId\>
* [x] AdminRemovePlayerFromSquad \<PlayerName\>
* [x] AdminRemovePlayerFromSquadById \<PlayerId\> 
* [x] AdminWarn \<NameOrSteamId\> \<WarnReason\> 
* [x] AdminWarnById \<PlayerId\> \<WarnReason\> 

## USAGE

### Create an instance
Instanciate the SquadServer RCON connection. This will connect to the server or throw an exception if it could not do so.
```php
/** @var SquadServer */
$server = new SquadServer(new ServerConnectionInfo('127.0.0.1', 21114, 'YourRconPassword'));
```

### Get current server population (Teams, Squads, Players)
Get the current population. This does use ListPlayers & ListSquads
to get the Teams, Squads and Players properly ordered.
```php
/** @var Population */
$population = $server->serverPopulation();

/** @var Team[] */
$teams = $population->getTeams();

// or

/** @var Player[] */
$players = $population->getPlayers();

// or

/** @var Player|null */
$player = $population->getPlayerBySteamId('76561197960287930');
```

### ListPlayers
Get the current Player list using the ListPlayers command.
This does not include disconnected players.
```php
/** @var Player[] */
$players = $server->listPlayers();
```

### Get disconnected Players
Get disconnected players using the ListPlayers command.
```php
/** @var Player[] */
$players = $server->listDisconnectedPlayers();
```

### ListSquads
Get currently active squads (and teams)
```php
/** @var Team[] */
$teams = $server->listSquads();
```

### AdminKick
Kick a player by name, SteamId or ingame id.
```php
/** @var bool */
$success = $server->adminKick('76561197960287930', 'Reason');

// or

/** @var bool */
$success = $server->adminKickById($player->getId(), 'Reason');
```

### AdminBan
Ban a player by name, SteamId or ingame id.
```php
/** @var bool */
$success = $server->adminBan('76561197960287930', '1h', 'Reason');

// or

/** @var bool */
$success = $server->adminBanById($player->getId(), '1h', 'Reason');
```

### Get the current map
Get the current map using the ShowNextMap command
```php
/** @var string */
$map = $server->currentMap();
```

### Get the next map
Get the next map using the ShowNextMap command
```php
/** @var string */
$map = $server->nextMap();
```

### AdminRestartMatch
Restart the current match
```php
/** @var bool */
$success = $server->adminRestartMatch();
```

### AdminEndMatch
End the current match
```php
/** @var bool */
$success = $server->adminEndMatch();
```

### AdminBroadcast
Broadcast message to all players on the server
```php
/** @var bool */
$success = $server->adminBroadcast('Hello from the other side');
```

### AdminChangeMap
Set the next map and end the current game immediately.
```php
/** @var bool */
$success = $server->adminChangeMap('Sumari AAS v1');
```

### AdminSetNextMap
Sets next map
```php
/** @var bool */
$success = $server->adminSetNextMap('Sumari AAS v1');
```

### AdminSetMaxNumPlayers
Set the maximum amount of players / slots
```php
/** @var bool */
$success = $server->adminSetMaxNumPlayers(80);
```

### AdminSetServerPassword
Set the server password
```php
/** @var bool */
$success = $server->adminSetServerPassword('secret');
```

### AdminForceTeamChange
Forces a player to the opposite team by providing the name or steamid.
```php
/** @var bool */
$success = $server->adminForceTeamChange('Name or SteamId');
```

### AdminForceTeamChangeById
Forces a player to the opposite team by providing the ingame Player id.
```php
/** @var bool */
$success = $server->adminForceTeamChangeById($player->getId());
```

### AdminDisbandSquad command.
Disbands a Squad by providing the Team id  / index & Squad id / index.
```php
/** @var bool */
$success = $server->adminDisbandSquad($team->getId(), $squad->getId());
```

### AdminRemovePlayerFromSquad
Removes a Player from his Squad by providing the Player name.
```php
/** @var bool */
$success = $server->adminRemovePlayerFromSquad('Name');
```

### AdminRemovePlayerFromSquadById
Removes a player from his Squad by providing the ingame Player id.
```php
/** @var bool */
$success = $server->adminRemovePlayerFromSquadById($player->getId());
```

### AdminWarn
Warns a Player by providing his name / steamid and a message.
```php
/** @var bool */
$success = $server->adminWarn('Name or SteamId', 'Warn Reason');
```

### AdminWarnById
Warns a Player by id.
```php
/** @var bool */
$success = $server->adminWarnById($player->getId(), 'Warn Reason');
```

## Important Note
Make sure to always close the connection manually or trigger a disconnect by destructing the object to preventt blocking the RCON server by using up it'S available connections.
```php
$server->disconnect();
// Or
unset($server);
```

## Special Thanks
* [SquadSlovenia](https://github.com/SquadSlovenia) (Intial creators)
* [Brozowski](https://github.com/Brozowski) (Major contributor)
* [ToG] subtlerod (Major contributions to the used SquadRcon implementation)
* [Thomas Smyth](https://github.com/Thomas-Smyth/SquadJS) (Creator of SquadJS, a great resource for Squad RCON).
