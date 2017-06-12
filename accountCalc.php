<?php error_reporting(0); ?>
<?php
require 'initiate.php';
$steam_id = $_GET['id'];


// If input is already numeric, assume it's a valid Steam ID
if (is_numeric($steam_id)) {
    $id = $steam_id;
}
// If the input is a profile URL, we extract the vanity ID
elseif (strstr($steam_id, 'steamcommunity.com/id/') !== false) {
    $steam_id = rtrim($steam_id, '/');
    $steam_id = substr($steam_id, strrpos($steam_id, '/') + 1);

    if (is_numeric($steam_id)) {
        $id = $steam_id;
    } else {
       // $json = SteamApiCall('ISteamUser', 'ResolveVanityURL', 'v0001', $api_key, ['vanityurl' => $id]);
        $jsonGetID = new Steam\Command\User\ResolveVanityUrl($steam_id,1);
        $jsonResult = $steam->run($jsonGetID);

        if ($jsonResult->response->success != 1) {
            die('Failed to resolve nickname.');
        }

        $id = $jsonResult->response->steamid;
    }
}
// Otherwise assume it's just the vanity ID, so resolve it
else {
    if (strpos($id, '://') === false) {
        $jsonGetID = new Steam\Command\User\ResolveVanityUrl($steam_id,1);
        $jsonResult = $steam->run($jsonGetID);

        if ($jsonResult->response->success != 1) {
            die('Failed to resolve nickname.');
        }

        $id = $jsonResult->response->steamid;
    }
}

if (!is_numeric($id)) {
    die('Steam ID error');
}




$game = new \Steam\Command\PlayerService\GetOwnedGames($id);
$result = $steam->run($game);
$gameCounter = $result->response->game_count;
$totalPrice = 0;
$priceperGame = 0;



$jsonGames = file_get_contents("https://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key={$key}&steamid={$id}&format=json&include_appinfo=1");
$gameList = json_decode($jsonGames);

//Get game icons
for ($i=0; $i < $gameCounter; ++$i ){
    $allgames2 = $gameList->response->games[$i];
    if (strlen($allgames2->name) > 32) {
        $allgames2->name = substr($allgames2->name, 0, 30).'..';
    }
    $allgames2->link = "http://steamcommunity.com/app/{$allgames2->appid}";
    $allgames2->image = "http://media.steampowered.com/steamcommunity/public/images/apps/{$allgames2->appid}/{$allgames2->img_logo_url}.jpg";
    $allgame2[] = $allgames2;
}

// Get not played games count
$notplayed = 0;
for ($i = 0; $i < $gameCounter; ++$i) {
    $game = $gameList->response->games[$i];
    if ($game->playtime_forever <= 0) {
        ++$notplayed;
    }
}
$notplayedPercent = round(($notplayed / $gameCounter) * 100);


// Get played recently (2 weeks)
$recentgame = new Steam\Command\PlayerService\GetRecentlyPlayedGames($id);
$recentgameResult = $steam->run($recentgame);
$played2weeks = $recentgameResult->response->total_count;
$max = $recentgameResult->response->total_count;
for ($i = 0; $i < $max; ++$i) {
    $game = $recentgameResult->response->games[$i];
    if (strlen($game->name) > 32) {
        $game->name = substr($game->name, 0, 30).'..';
    }
    $game->link = "http://steamcommunity.com/app/{$game->appid}";
    $game->image = "http://media.steampowered.com/steamcommunity/public/images/apps/{$game->appid}/{$game->img_icon_url}.jpg";
    $games[] = $game;
}



//Get appids
for($i = 0; $i < $gameCounter; ++$i){
    $allgames = $result->response->games[$i];
    $allgame[] = $allgames;

}

//List of appids separated by comma (10,40,240,480,etc
$out = "";
foreach($allgame as $allgames){
    $out .= $allgames->appid.",";
    //$ids = implode(",",$allgames->appid);
}
$out = substr($out,0,-1)."";
//echo $out;




//get prices of games
try {
    if (strlen($out) > 2000){
        $out1 = substr($out, 0, 4005);
        $out2 = substr($out, 4005, -1);
        $json1 = file_get_contents("http://store.steampowered.com/api/appdetails?appids={$out1}&cc=USD&filters=price_overview");
        $json2 = file_get_contents("http://store.steampowered.com/api/appdetails?appids={$out2}&cc=USD&filters=price_overview");
        $allGames1 = json_decode($json1, true);
        $allGames2 = json_decode($json2, true);
        foreach($allgame as $allgames){
            try {
                $priceperGame = $allGames1[$allgames->appid]['data']['price_overview']['final'];
                $priceperGame2 = $allGames2[$allgames->appid]['data']['price_overview']['final'];
                $totalPrice = $totalPrice + $priceperGame + $priceperGame2;
            }
            catch(Exception $e){}
        }
    }
    else{
        $json = file_get_contents("https://store.steampowered.com/api/appdetails?appids={$out}&cc=USD&filters=price_overview");
        $allGames = json_decode($json, true);
        foreach($allgame as $allgames){
            try {
                $priceperGame = $allGames[$allgames->appid]['data']['price_overview']['final'];
                $totalPrice = $totalPrice + $priceperGame;
            }
            catch(Exception $e){}
        }
    }
}
catch(ErrorException $e){}



class PersonaState
{
    const Offline        = 0;
    const Online         = 1;
    const Busy           = 2;
    const Away           = 3;
    const Snooze         = 4;
    const LookingToTrade = 5;
    const LookingToPlay  = 6;
}


//get profile info
$playerSummary = new Steam\Command\User\GetPlayerSummaries(array ($id));
$summaryResult = $steam->run($playerSummary);
foreach($summaryResult->response->players as $player) {
    $avatar = $player->avatarfull;
    $profileName = $player->personaname;
    $realname = $player->realname;
    $country = $player->loccountrycode;
    $created = isset($player->timecreated) ? date('Y-m-d', $player->timecreated) : 0;
    $steamid = $player->steamid;
    $profileLink = $player->profileurl;
    $private = $player->communityvisibilitystate;
        //See if player ingame
        $ingame = isset($player->gameid) ? true : false;
        $profileState = $player->personastate;
        if ($ingame) {
            $game->appid = $player->gameid;
            $game->link = "http://steamcommunity.com/app/{$game->appid}";
            $game->image = "http://media.steampowered.com/steamcommunity/public/images/apps/{$game->appid}/{$game->img_icon_url}.jpg";
            $game->logo = "http://media.steampowered.com/steamcommunity/public/images/apps/{$game->appid}/{$game->img_logo_url}.jpg";
            $game->header = "http://cdn.akamai.steamstatic.com/steam/apps/{$game->appid}/header.jpg";
            $game->name = $player->gameextrainfo;
        }
        // Set text color
        if ($ingame) {
            $avatarborder = 'rgb(131,179,89)';
        } else {
            switch ($profileState) {
                case PersonaState::Online:
                case PersonaState::Busy:
                case PersonaState::Away:
                case PersonaState::Snooze:
                case PersonaState::LookingToTrade:
                case PersonaState::LookingToPlay:
                    $avatarborder = '#4787A0';
                    break;
                case PersonaState::Offline:
                default:
                    $avatarborder = 'grey';
                    break;
            }
        }
}





//get bans
$banInfo = new Steam\Command\User\GetPlayerBans(array ($id));
$banResult = $steam->run($banInfo);
foreach($banResult->players as $player2){
    $vacban = $player2->VACBanned ? 'BANNED' : 'NOT BANNED';
    $communityban = $player2->CommunityBanned ? 'BANNED' : 'NOT BANNED';
}



//total value
$value = $totalPrice / 100;
//echo "total value: $"; echo $value; echo "\n";


//total pennies
//echo "total games: "; echo $gameCounter; echo "\n";
//echo "total pennies: "; echo $totalPrice; echo "\n";

//pound in pennies
$pennyPerPound = 181.4368;
$pennyCalculation = $totalPrice / $pennyPerPound;
//echo "pounds in pennies: "; echo $pennyCalculation; echo "\n";

//actual pounds
$gameWeight = 0.3125;
$weightCalculation = $gameCounter * $gameWeight;
//echo "actual pounds: "; echo $weightCalculation; echo "\n";

//chickens
$chickenWeight = 1.9;
$chickenCalculation = $weightCalculation / $chickenWeight;
//based on Booted Bantam chicken

//cows
$cowWeight = 2400;
$cowCalculation = $weightCalculation / $cowWeight;
//based on male cow

//goats
$goatWeight = 60;
$goatCalculation = $weightCalculation / $goatWeight;
//based on boer goat

//dragon
$dragonWeight = 22046.2;
$dragonCalculation = $weightCalculation / $dragonWeight;
//based on lightweight dragon calculation from dnd

//gold bar
$goldbarWeight = 27.33732;
$goldbarCalculation = $weightCalculation / $goldbarWeight;
//based on gold idiot

//unladen african swallow
$africanswallowWeight = 0.0462971128238593;
$africanswallowCalculation = $weightCalculation / $africanswallowWeight;
//based on unladen african swallow

//this sites developers
$devWeight = 147.5;
$devCalculation = $weightCalculation / $devWeight;
//averaged between 2 devs

if (!$summaryResult || !$summaryResult->response || !$recentgameResult || !$recentgameResult->response) {
    die('Failed to get profile data!');
}
?>