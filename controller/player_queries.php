<?php
require_once('../model/team.php');
require_once('../model/game.php');
require_once('../model/player.php');
require_once('DBConnection.php');

function GetPlayerInfo($playerid)
{
    $con = CreateConnection();
    $pi = mysqli_query($con, "select * from player_tbl p where p.playerid = ". $playerid . ";");
    return mysqli_fetch_array($pi);
    mysqli_close($con);
}

//Debugging only
function GetESPNPlayerStats($playerid)
{
    $con = CreateConnection();
    $pi = mysqli_query($con, "select * from player_tbl p 
        inner join espn_player_stats_tbl ps
        on p.espn_id = ps.playerid
        where p.playerid = ". $playerid . ";");
    return mysqli_fetch_array($pi);
    mysqli_close($con);
}

function GetPlayerRatings($playerid)
{
    $con = CreateConnection();
    $pi = mysqli_query($con, "select pr.* from player_tbl p 
        inner join player_ratings_tbl pr
        on p.playerid = pr.playerid
        where p.playerid = ". $playerid . ";");
    return mysqli_fetch_assoc($pi);
    mysqli_close($con);
}

//TODO -- player stats
function GetPlayerStats($playerid)
{
    return null;
}

?>