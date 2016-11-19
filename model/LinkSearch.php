<?php

include_once "M_MSQL.php";
include_once "M_Link.php";
include_once "M_Game.php";
include_once "M_Price.php";
include_once "M_Search.php";
include_once "M_Total.php";
include_once "M_PriceParser.php";
include_once "LinkSearchParser.php";

/* лимит выборки цен */
define(STEP, 3);
define(STANDBY, 15);

$arSite = array(
  'steambuy' => 1,
  'gameray' => 3,
  'lozman' => 4,
  'gamepark' => 6,
  'gzonline' => 7,
  'steampay' => 8,
  'playgames' => 12,
  'nextgame' => 13,
  'gamebuy' => 14,
  'playo' => 15
  

  //'gamerepublic' => 9
  //'steam' => 2,
  //'roxen' => 5
);

//const DANDYLAND_ID = 10;
//const GAME93W_ID = 16;


/**
 * Достаем ссылки с playo
 */
function playo($gameName, $siteId)
{
  global $arLinks;
  $host = 'https://playo.ru/goods/';
  $partner = '?s=d1v9c00v';
  $hostGameName = str_replace(' ', '_', $gameName);
  $link = $host . strtolower($hostGameName) . "/";

  if(checkLink($link . $partner, $siteId))
  {
    $arLinks[ $gameName ][] = array(
      'link' => $link . $partner,
      'site_id' => $siteId,
      'platform_id' => 1
    );
  }

  return $arLinks;
}


/**
 * Достаем ссылки с gamebuy
 */
function gamebuy($gameName, $siteId)
{
  global $arLinks;
  $host = 'https://www.gamebuy.ru/';
  $hostGameName = str_replace(' ', '-', $gameName) . "-";

  $arPostfix = array(
    'xboxone' => 'xbox-one',
    'xbox360' => 'x360',
    'ps4' => 'ps4',
    'ps3' => 'ps3',
    'pc' => 'pc'
  );
  
  $arPlatforms = array(
    1 => array('pc'),
    2 => array('ps3'), 
    3 => array('ps4'), 
    4 => array('xbox360'),
    5 => array('xboxone'));

  foreach ($arPlatforms as $platformId => $platform) 
  {
    foreach ($platform as $name) 
    {
      $link = $host . $name . "/game/" . $hostGameName . $arPostfix[$name];
      if(checkLink($link, $siteId))
      {
        $arLinks[ $gameName ][] = array(
          'link' => $link,
          'site_id' => $siteId,
          'platform_id' => $platformId
        );
      }
    }
  }

  return $arLinks;
}


/**
 * Достаем ссылки с nextgame
 */
function nextgame($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://nextgame.net/catalog/';
  $hostGameName = str_replace(' ', '-', $gameName) . "-";

  $arPostfix = array(
    'ps3' => 'For-PS3',
    'ps4' => 'For-PS4',
    'xbox360' => 'For-Xbox-360',
    'xboxone' => 'For-Xbox-One',
    'pc' => 'For-PC'
  );

  $arPlatformPath = array(
    'ps3' => 'playstation3/games/',
    'ps4' => 'playstation3/games_ps4/',
    'xbox360' => 'xbox360/games/',
    'xboxone' => 'xbox360/games_xbox_one/',
    'pc' => 'pc/games/'
  );
  
  $arPlatforms = array(
    1 => array('pc'),
    2 => array('ps3'), 
    3 => array('ps4'), 
    4 => array('xbox360'),
    5 => array('xboxone'));

  foreach ($arPlatforms as $platformId => $platform) 
  {
    foreach ($platform as $name) 
    {
      $link = $host . $arPlatformPath[$name] . $hostGameName . $arPostfix[$name] . "/";
      if(checkLink($link, $siteId))
      {
        $arLinks[ $gameName ][] = array(
          'link' => $link,
          'site_id' => $siteId,
          'platform_id' => $platformId
        );
      }
    }
  }

  return $arLinks;
}


/**
 * Достаем ссылки с gzonline
 */
function playgames($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://playgames.ru/';
  $hostGameName = str_replace(' ', '-', $gameName);
  
  $arPlatforms = array(
    1 => array('pc'), 
    2 => array('ps3'), 
    3 => array('ps4'), 
    4 => array('xbox-360'), 
    5 => array('xbox-one'));

  foreach ($arPlatforms as $platformId => $platform) 
  {
    foreach ($platform as $name) 
    {
      $link = $host . strtolower($hostGameName) . "-" . $name . "/";
      if(checkLink($link, $siteId))
      {
        $arLinks[ $gameName ][] = array(
          'link' => $link,
          'site_id' => $siteId,
          'platform_id' => $platformId
        );
      }
    }
  }

  return $arLinks;
}


/**
 * Достаем ссылки с gzonline
 */
function gzonline($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://gzonline.ru/catalog/detail/';
  $hostGameName = str_replace(' ', '-', $gameName);
  
  $arPlatforms = array(
    1 => array('pc'), 
    2 => array('ps3'), 
    3 => array('ps4'), 
    5 => array('xone'));

  foreach ($arPlatforms as $platformId => $platform) 
  {
    foreach ($platform as $name) 
    {
      $link = $host . $hostGameName . "-_rus-" . $name . "/";
      if(checkLink($link, $siteId))
      {
        $arLinks[ $gameName ][] = array(
          'link' => $link,
          'site_id' => $siteId,
          'platform_id' => $platformId
        );
      }
    }
  }

  return $arLinks;
}


/**
 * Достаем ссылки с gamepark
 */
function gamepark($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://www.gamepark.ru/';
  $partner = 'https://ad.admitad.com/g/af8ef42a17d9b214c029e8b31ead25/?ulp=';
  $hostGameName = str_replace(' ', '', $gameName);

  $arPlatformPath = array(
    'ps3' => 'playstation3/games/',
    'ps4' => 'playstation4/games/',
    'xbox360' => 'xbox360/games/',
    'xboxone' => 'xboxone/games/',
    'pc' => 'pc/games/'
  );
  
  $arPlatforms = array(
    1 => array('pc'),
    2 => array('ps3'), 
    3 => array('ps4'), 
    4 => array('xbox360'),
    5 => array('xboxone'));

  foreach ($arPlatforms as $platformId => $platform) 
  {
    foreach ($platform as $name) 
    {
      $link = $host . $arPlatformPath[$name] . $hostGameName . $name . "/";
      if(checkLink($link, $siteId))
      {
        $link = str_replace(':', '%3A', $link);
        $link = str_replace('/', '%2F', $link);
        $arLinks[ $gameName ][] = array(
          'link' => $partner . $link,
          'site_id' => $siteId,
          'platform_id' => $platformId
        );
      }
    }
  }

  return $arLinks;
}


/**
 * Достаем ссылки с gameray
 */
function gameray($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://www.gameray.ru/';
  $hostGameName = str_replace(' ', '-', $gameName);
  $link = $host . strtolower($hostGameName) . "/";

  if(checkLink($link, $siteId))
  {
    $arLinks[ $gameName ][] = array(
      'link' => $link,
      'site_id' => $siteId,
      'platform_id' => 1
    );
  }

  return $arLinks;
}


/**
 * Достаем ссылки с steampay
 */
function steampay($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://steampay.com/game/';
  $partner = '?agent=672517';
  $hostGameName = str_replace(' ', '-', $gameName);
  $link = $host . strtolower($hostGameName);

  if(check404($link . $partner))
  {
    if(checkLink($link, $siteId))
    {
      $arLinks[ $gameName ][] = array(
        'link' => $link . $partner,
        'site_id' => $siteId,
        'platform_id' => 1
      );
    }
  }

  return $arLinks;
}


/**
 * Достаем ссылки с steambuy
 */
function steambuy($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://steambuy.com/steam/';
  $partner = '?partner=672517';
  $hostGameName = str_replace(' ', '-', $gameName);
  $linkBase = $host . $hostGameName;

  $link = $linkBase . $name . '/';
  if(check404($link . $partner))
  {
    if(checkLink($link, $siteId))
    {
      $arLinks[ $gameName ][] = array(
        'link' => $link . $partner,
        'site_id' => $siteId,
        'platform_id' => 1
      );
    }
  }

  return $arLinks;
}


/**
 * Достаем ссылки с Lozman-Games
 */
function lozman($gameName, $siteId)
{
  global $arLinks;
  $host = 'http://lozman-games.ru/catalog/igry/';
  $hostGameName = str_replace(' ', '_', $gameName);
  $linkBase = $host . $hostGameName;
  
  $arPlatforms = array(
    1 => array('pc', 'pc_klyuch', 'dvd_pc'),
    2 => array('ps3'), 
    3 => array('ps4'), 
    4 => array('xbox_360'),
    5 => array('xbox_one'));

  foreach ($arPlatforms as $platformId => $platform) 
  {
    foreach ($platform as $name) 
    {
      if($name != '')
      {
        $name = '_' . $name;
      }

      $link = $linkBase . $name . '/';
      if(checkLink($link, $siteId))
      {
        $arLinks[ $gameName ][] = array(
          'link' => $link,
          'site_id' => $siteId,
          'platform_id' => $platformId
        );
      }
    }
  }

  return $arLinks;
}


function check404($link)
{
  //echo "<br>Checking " . $link;
  $handle = curl_init($link);
  curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
  $response = curl_exec($handle);

  $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
  curl_close($handle);

  if($httpCode == 404) 
  {
    //echo " [-] 404<br>";
    return false;
  }

  return $response;
}


function checkLink($link, $siteId)
{
  global $linkParser;

  $response = check404($link);
  if(!$response)
  {
    return false;
  }

  $result = $linkParser->parse($response, $siteId);
  if(!$result)
  {
    //echo " [-] BAD LINK <br>";
  }
  else
  {
    //echo " [+] OK <br>";
  }

  return $result;
}

// ------------------------ Игры для поиска и добавления ----------------------
$games = array(
  'Surgeon Simulator Anniversary Edition'
);


$arLinks = array();
$linkParser = LinkSearchParser::Instance();
$mSearch = M_Search::Instance();

foreach($arSite as $siteName => $siteId)
{
  foreach($games as $game)
  {
    echo "\n";
    $siteName($game, $siteId);
  }
}


$mGame = M_Game::Instance();
$mLink = M_Link::Instance();
$mPrice = M_Price::Instance();
$mTotal = M_Total::Instance();
$mPriceParser = M_PriceParser::Instance();

echo "<br>Adding games ...<br>";

foreach($arLinks as $gameName => $game)
{
  $genreId = 1;
  $image = 
    '/upload/images/' . str_replace(' ', '_', strtolower($gameName)) . '.jpg';

  $gameId = $mGame->addGame($gameName, $genreId, $image);
  $mGame->addGameKeywords($gameId, $gameName);

  if($gameId)
  {
    $linksId = $mLink->addLinkExt($game);
    $priceList = $mPriceParser->parse($linksId);
    $priceId = $mPrice->addPrice($priceList);
    $totalId = $mTotal->addTotal($gameId, $linksId, $priceId);
  }

  if($totalId)
  {
    echo $gameName . " : SUCCESS<br>";
  }
  else
  {
    echo $gameName . " : FAILED<br>";
  }
}

echo "<br><pre>";
print_r($arLinks);
echo "</pre>";

//sleep(STANDBY);
