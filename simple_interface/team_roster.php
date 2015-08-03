<?php
require_once('../model/team.php');
require_once('../model/game.php');
require_once('../model/player.php');
require_once('../controller/team_queries.php');
require_once('bootstrap.php');
require_once('menu.php');
require_once('display_util.php');

$teamid = $_REQUEST['id'] ;

$team_info = GetTeamInfo($teamid);

PrintHeader($team_info['name'] . " Roster");
PrintMenu($teamid, $teamid, $team_info['conferenceid']);

$roster = GetRoster($teamid);

echo "<h3>Roster</h3>";

echo "<table class=\"table\"><tbody>";

foreach($roster as $player)
{
  DisplayPlayerRow($player);
}

echo "<tbody></table>";

PrintFooter();

function DisplayPlayerRow($player)
{
    echo "<tr>";
    foreach($player as $key => $value) {
        if($key == "playerid")
        {
            echo "<td><a class=\"glyphicon glyphicon-info-sign\" href=\"player_overview.php?id=". $value."\"></a></td>";
        }
        else if($key == "ht")
        {
            echo "<td>". HeightDisplayString($value)."</td>";
        }
        else
        {
            echo "<td>". $value."</td>";
        }
    }
    echo "</tr>";
}

?>
