<?php

namespace DSG\SquadRCON\Tests;

class ResponseSamples {
    public static function listPlayers(bool $empty = false) : string
    {
        if (!$empty) {
            return 
            '----- Active Players -----
            ID: 21 | SteamID: 76561198054760135 | Name: *Ragnar_Lotbrock82* | Team ID: 2 | Squad ID: 3
            ID: 41 | SteamID: 76561198834340306 | Name: Admiral John | Team ID: 2 | Squad ID: 10
            ID: 39 | SteamID: 76561197963356626 | Name: Aegmar | Team ID: 2 | Squad ID: 10
            ID: 82 | SteamID: 76561198450388317 | Name: HugoBadAss92_DEU | Team ID: 1 | Squad ID: 8
            ID: 64 | SteamID: 76561198056033287 | Name: Bene o_O | Team ID: 2 | Squad ID: 2
            ID: 79 | SteamID: 76561198039847055 | Name: [GER] BlueBoxT2 | Team ID: 2 | Squad ID: 5
            ID: 52 | SteamID: 76561198349811676 | Name: Borg | Team ID: 1 | Squad ID: 7
            ID: 61 | SteamID: 76561198110126580 | Name: BreiterBart | Team ID: 2 | Squad ID: 8
            ID: 53 | SteamID: 76561198202943394 | Name: [1JGKP]Bud-Muecke (YT) | Team ID: 1 | Squad ID: 3
            ID: 15 | SteamID: 76561198314773221 | Name: ]B[S Colfax | Team ID: 2 | Squad ID: 2
            ID: 50 | SteamID: 76561198019320471 | Name: D. | Team ID: 1 | Squad ID: 7
            ID: 56 | SteamID: 76561198126686654 | Name: ]B[S DeltaMaxx | Team ID: 2 | Squad ID: 2
            ID: 73 | SteamID: 76561198052074428 | Name: Der Knecht | Team ID: 1 | Squad ID: 4
            ID: 4 | SteamID: 76561198078017064 | Name: DetlevDuese | Team ID: 2 | Squad ID: 2
            ID: 81 | SteamID: 76561198420739023 | Name: [66th] Devilukedude | Team ID: 2 | Squad ID: 1
            ID: 28 | SteamID: 76561197991020488 | Name: DontCallMeZiege | Team ID: 1 | Squad ID: 7
            ID: 80 | SteamID: 76561198013218109 | Name: Doom | Team ID: 1 | Squad ID: 3
            ID: 36 | SteamID: 76561199029350316 | Name: Dr. Grinspoon | Team ID: 1 | Squad ID: 3
            ID: 45 | SteamID: 76561198015963880 | Name: EisGecko | Team ID: 2 | Squad ID: 2
            ID: 60 | SteamID: 76561197965452181 | Name: F.Krueger | Team ID: 1 | Squad ID: 8
            ID: 30 | SteamID: 76561197995266740 | Name: [1JGKP]FACE | Team ID: 1 | Squad ID: 3
            ID: 24 | SteamID: 76561198124649264 | Name: FatKidsareHardtoKidnap | Team ID: 2 | Squad ID: 5
            ID: 3 | SteamID: 76561198159379914 | Name: Flexルーシー | Team ID: 1 | Squad ID: 4
            ID: 25 | SteamID: 76561198042102731 | Name: =EBS= FuriousBaco | Team ID: 2 | Squad ID: 9
            ID: 49 | SteamID: 76561198203311975 | Name: [BOS]Garantiefall | Team ID: 1 | Squad ID: 7
            ID: 43 | SteamID: 76561199043221431 | Name: GenosseChang | Team ID: 2 | Squad ID: 10
            ID: 10 | SteamID: 76561198079809154 | Name: GunRunner | Team ID: 2 | Squad ID: 5
            ID: 87 | SteamID: 76561198058200414 | Name:  ]B[S HappyBashing | Team ID: 1 | Squad ID: 8
            ID: 68 | SteamID: 76561198452778797 | Name: [CHAOS]Herr Busch | Team ID: 2 | Squad ID: 10
            ID: 75 | SteamID: 76561198996340755 | Name: [1JGKP]Invictus_Painzz | Team ID: 1 | Squad ID: 4
            ID: 44 | SteamID: 76561198000419593 | Name: Ivan | Team ID: 1 | Squad ID: 8
            ID: 77 | SteamID: 76561198068361421 | Name: Jannik | Team ID: 1 | Squad ID: 6
            ID: 54 | SteamID: 76561198102527401 | Name: Jim2509 | Team ID: 1 | Squad ID: 5
            ID: 63 | SteamID: 76561198866437420 | Name: K1n9sjulian | Team ID: 1 | Squad ID: 3
            ID: 62 | SteamID: 76561198000485005 | Name: Kalle | Team ID: 2 | Squad ID: 8
            ID: 38 | SteamID: 76561198065331632 | Name: [66th] Lars555 | Team ID: 2 | Squad ID: 1
            ID: 74 | SteamID: 76561198015620832 | Name: [66th] LarsVegas | Team ID: 2 | Squad ID: 1
            ID: 58 | SteamID: 76561198047078003 | Name: [KSK] Ltd_Dan_FirstPlatoon | Team ID: 2 | Squad ID: 4
            ID: 59 | SteamID: 76561198151799852 | Name: Ludi | Team ID: 2 | Squad ID: 2
            ID: 48 | SteamID: 76561198011805749 | Name: Mr.Fluffkin | Team ID: 2 | Squad ID: 5
            ID: 6 | SteamID: 76561198090617959 | Name: [KSK] NomadODST | Team ID: 2 | Squad ID: 4
            ID: 33 | SteamID: 76561198014626760 | Name: Osama im Laden | Team ID: 1 | Squad ID: 4
            ID: 1 | SteamID: 76561197973804097 | Name: Pagan | Team ID: 2 | Squad ID: 10
            ID: 9 | SteamID: 76561198066122557 | Name: Philbo | Team ID: 2 | Squad ID: 10
            ID: 78 | SteamID: 76561198021646413 | Name: Pulpo | Team ID: 2 | Squad ID: 5
            ID: 19 | SteamID: 76561198064620452 | Name: Reynge | Team ID: 1 | Squad ID: 3
            ID: 71 | SteamID: 76561198063947945 | Name: Schmongo | Team ID: 1 | Squad ID: 7
            ID: 27 | SteamID: 76561198060098041 | Name: Scianda | Team ID: 2 | Squad ID: 2
            ID: 40 | SteamID: 76561198429663037 | Name: [1JGKP]StryexX | Team ID: 1 | Squad ID: 1
            ID: 66 | SteamID: 76561198199090610 | Name: [1JGKP]Stundenplan | Team ID: 1 | Squad ID: 3
            ID: 22 | SteamID: 76561198237497942 | Name: Sykkel | Team ID: 2 | Squad ID: 6
            ID: 7 | SteamID: 76561198112373408 | Name: [T.E.]Thomas | Team ID: 1 | Squad ID: 5
            ID: 23 | SteamID: 76561198127506682 | Name: Thrasher | Team ID: 2 | Squad ID: 2
            ID: 12 | SteamID: 76561198232875588 | Name: Timterimtimtim | Team ID: 1 | Squad ID: N/A
            ID: 11 | SteamID: 76561198318496586 | Name: White97 | Team ID: 2 | Squad ID: 5
            ID: 0 | SteamID: 76561199033007215 | Name: TwixXI3eatX | Team ID: 2 | Squad ID: 5
            ID: 70 | SteamID: 76561198057668694 | Name: Xraptor92 | Team ID: 1 | Squad ID: 8
            ID: 14 | SteamID: 76561198254291009 | Name: Yaohmaji | Team ID: 2 | Squad ID: 8
            ID: 55 | SteamID: 76561198143940645 | Name: [66th]Gorne1 | Team ID: 2 | Squad ID: 1
            ID: 13 | SteamID: 76561198141660689 | Name: [TTW-Gen]RaiderRange | Team ID: 2 | Squad ID: 7
            ID: 85 | SteamID: 76561198825610802 | Name: _.Rambo._ | Team ID: 1 | Squad ID: 4
            ID: 2 | SteamID: 76561198126882327 | Name: [66th] be water, DMT | Team ID: 2 | Squad ID: 1
            ID: 67 | SteamID: 76561197961119401 | Name: bigXopti | Team ID: 2 | Squad ID: 7
            ID: 76 | SteamID: 76561198249719990 | Name: dorfkeeper | Team ID: 1 | Squad ID: 6
            ID: 18 | SteamID: 76561198082093793 | Name: [feli]f17897 | Team ID: 1 | Squad ID: 3
            ID: 31 | SteamID: 76561197990281056 | Name: [BOS]mobb | Team ID: 1 | Squad ID: 2
            ID: 17 | SteamID: 76561197960545042 | Name: qu1nk | Team ID: 2 | Squad ID: 5
            ID: 26 | SteamID: 76561199020956108 | Name: [66th] ruhfe3 | Team ID: 2 | Squad ID: 1
            ID: 46 | SteamID: 76561197989256039 | Name: [BOS] s1ckk_ | Team ID: 1 | Squad ID: 7
            ID: 8 | SteamID: 76561198827732228 | Name: sheining | Team ID: 1 | Squad ID: 3
            ID: 29 | SteamID: 76561197982549742 | Name: [BOS] soololi | Team ID: 1 | Squad ID: 7
            ID: 83 | SteamID: 76561197994356835 | Name: [NG]veritas | Team ID: 1 | Squad ID: 8
            ID: 32 | SteamID: 76561197970328021 | Name: wowa | Team ID: 1 | Squad ID: 8
            ID: 69 | SteamID: 76561198010474901 | Name: █SpoonY█ | Team ID: 1 | Squad ID: 7
            ID: 65 | SteamID: 76561197978096854 | Name: ☆︎tackleberry☆ | Team ID: 2 | Squad ID: 2
            ID: 57 | SteamID: 76561198190418050 | Name: ♥K1ng♥Cr3w♥ -iwnl- | Team ID: 1 | Squad ID: 7
            ID: 5 | SteamID: 76561198320549995 | Name: ✝YungAcid ツ♤ | Team ID: 1 | Squad ID: 8
            ----- Recently Disconnected Players [Max of 15] -----
            ID: 88 | SteamID: 76561198040530100 | Since Disconnect: 03m.15s | Name: ChiefWiggum
            ID: 84 | SteamID: 76561198007358472 | Since Disconnect: 01m.48s | Name: [=♠=]Thrillhouse0580
            ID: 42 | SteamID: 76561198018627565 | Since Disconnect: 00m.03s | Name: Psycho';
        } else {
            return 
            '----- Active Players -----
            ----- Recently Disconnected Players [Max of 15] -----';
        }
    }

    public static function listSquads(bool $empty = false) : string
    {
        if (!$empty) {
            return 
            '----- Active Squads -----
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
            ID: 10 | Name: GER INF | Size: 6 | Locked: False';
        } else {
            return 
            '----- Active Squads -----
            Team ID: 1 (United States Army)
            Team ID: 2 (Russian Ground Forces)';
        }
    }
}