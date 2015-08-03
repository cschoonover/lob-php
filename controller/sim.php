<?php
require_once('../model/game.php');
require_once('../model/team.php');
require_once('DBConnection.php');
require_once('scheduler.php');
require_once('team_queries.php');

function GetCurrentDate()
{
    $con = CreateConnection();
    $result = mysqli_query($con,"select * from date_counter_tbl;");
    $row = mysqli_fetch_array($result);
    $day = $row['simdate'];
    mysqli_close($con);
    return $day;
}

function SimOneDay()
{
    $con = CreateConnection();
    $result = mysqli_query($con,"update date_counter_tbl set simdate = DATE_ADD(simdate, INTERVAL 1 DAY);");
    mysqli_close($con);
    SimToDate();
}


function SimToDate()
{
    $con = CreateConnection();
    $games = array();
    
    $result = mysqli_query($con,"select * from game_tbl where date < (select simdate from date_counter_tbl) and homescore is null;");
    
    while($row = mysqli_fetch_array($result))
    {
     $game = new Game($row['hometeamid'], $row['awayteamid'], $row['date']);
     $game->gameid = $row['gameid'];
     $games[] = $game;
    }
    mysqli_close($con);
    foreach($games as $gameToSim)
    {
        SimGame($gameToSim);
    }
}

function SimGame($game)
{
    $con = CreateConnection();
    $t1overall = GetSimpleOverall($game->home_team);
    $t2overall = GetSimpleOverall($game->away_team);
    $t1score = mt_rand($t1overall - 19, $t1overall + 21);
    $t2score = mt_rand($t2overall - 20, $t2overall + 20);
    if($t1score == $t2score)
    {
        if(mt_rand(0,1) == 0)
        {
            $t1score++;
        }
        else
        {
            $t2score++;
        }
    }
    $result = mysqli_query($con,"update game_tbl set homescore = " . $t1score .
        ", awayscore = ". $t2score. 
        " where gameid = " . $game->gameid . ";");
    mysqli_close($con);
}

function ResetSchedule()
{
    $con = CreateConnection();
    $result = mysqli_query($con,"delete from game_tbl;");
    mysqli_close($con);
    CreateMatchupsSchedule();
}

?>