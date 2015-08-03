<?php
require_once('../model/team.php');
require_once('../model/game.php');
require_once('../model/player.php');
require_once('DBConnection.php');

function GetSchedule($teamid)
{
    $con = CreateConnection();
    $games = array();
    
    $result = mysqli_query($con,"select * from team_tbl t 
    inner join game_tbl g 
    on t.teamid = g.awayteamid 
    where g.hometeamid = " . $teamid .
    " union
    select * from team_tbl t 
    inner join game_tbl g 
    on t.teamid = g.hometeamid 
    where g.awayteamid = " . $teamid .
    ";");
    
     
    while($row = mysqli_fetch_array($result))
    {
     $game = new Game(new Team($row['hometeamid'], 0, $row['name']), new Team($row['awayteamid'], 0, $row['name']), $row['date']);
     $game->homescore = $row['homescore'];
     $game->awayscore = $row['awayscore'];
     $games[] = $game;
    }
    mysqli_close($con);
    return $games;
}

function GetRecord($teamid)
{
    $con = CreateConnection();
    $result = mysqli_query($con, "select (select count(*) from game_tbl where hometeamid = ".$teamid." and homescore>awayscore) + 
    (select count(*) from game_tbl where awayteamid = ".$teamid." and homescore<awayscore)  as wins, 
    (select count(*) from game_tbl where hometeamid = ".$teamid." and homescore<awayscore) + 
    (select count(*) from game_tbl where awayteamid = ".$teamid." and homescore>awayscore) as losses;");
    return mysqli_fetch_array($result);
}

function GetTeamInfo($teamid)
{
    $con = CreateConnection();
    $team_info_result = mysqli_query($con, "select * from team_tbl t inner join conference_to_team_tbl ct on 
    t.teamid = ct.teamid
    where t.teamid = ". $teamid . ";");
    return mysqli_fetch_array($team_info_result);
    mysqli_close($con);
}

function GetStarters($teamid)
{
    $con = CreateConnection();
    $players = array();
    $result = mysqli_query($con, "select p.*, po.overall from 
        team_tbl t
        inner join team_to_player_tbl tp
        on t.teamid = tp.teamid
        inner join player_tbl p
        on tp.playerid = p.playerid
        inner join player_overall_vw po
        on p.playerid = po.playerid
        where t.teamid = $teamid order by overall desc;");
    while($row = mysqli_fetch_array($result))
    {
        $p = new Player($row['playerid']);
        $p->jersey_no = $row['jersey_no'];
        $p->firstname= $row['firstname'];
        $p->lastname= $row['lastname'];
        $p->age= $row['age'];
        $p->pos= $row['pos'];
        $p->ht= $row['ht'];
        $p->wt = $row['wt'];
        $p->class_standing = $row['class'];
        $p->redshirt = $row['redshirt'];
        $p->hometown = $row['hometown'];
        $p->overall= $row['overall'];
        $players[] = $p;
    }
    mysqli_close($con);
    return array_slice($players, 0, 5);
}

function GetSimpleOverall($teamid)
{
    $con = CreateConnection();
    $team_info_result = mysqli_query($con, "select avg(overall) from (select p.lastname, p.pos, po.* from 
        team_tbl t
        inner join team_to_player_tbl tp
        on t.teamid = tp.teamid
        inner join player_tbl p
        on tp.playerid = p.playerid
        inner join player_overall_vw po
        on p.playerid = po.playerid
        where t.teamid = ". $teamid ." order by overall desc limit 5) as starters;");
    mysqli_close($con);
    $result = mysqli_fetch_array($team_info_result);
    return $result[0];
}

function GetRoster($teamid)
{
    $con = CreateConnection();
    $players = array();
    $result = mysqli_query($con, "select p.* from 
        team_tbl t
        inner join team_to_player_tbl tp
        on t.teamid = tp.teamid
        inner join player_tbl p
        on tp.playerid = p.playerid
        where t.teamid = ". $teamid . ";");
    while($row = mysqli_fetch_array($result))
    {
        $p = new Player($row['playerid']);
        $p->jersey_no = $row['jersey_no'];
        $p->firstname= $row['firstname'];
        $p->lastname= $row['lastname'];
        $p->age= $row['age'];
        $p->pos= $row['pos'];
        $p->ht= $row['ht'];
        $p->wt = $row['wt'];
        $p->class_standing = $row['class'];
        $p->redshirt = $row['redshirt'];
        $p->hometown = $row['hometown'];
        $players[] = $p;
    }
    mysqli_close($con);
    return $players;
}
  
?>