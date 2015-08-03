<?php
require_once('../model/team.php');
require_once('../model/game.php');
require_once('../controller/team_queries.php');
require_once('bootstrap.php');
require_once('menu.php');



$teamid = $_REQUEST['id'] ;



$team_info = GetTeamInfo($teamid);

//var_dump ($team_info);

PrintHeader($team_info['name'] . " Team Overview");
PrintMenu($teamid, $teamid, $team_info['conferenceid']);


$record = GetRecord($teamid);

echo "<h3>".$team_info['name']." (". $record['wins']."-" . $record['losses'] . ")</h3>";

$games = GetSchedule($teamid);

usort($games, function($a, $b) {
    return strtotime($a->date) - strtotime($b->date);
});
 

$starters = GetStarters($teamid);
echo "<h3>Starting Lineup</h3>";
echo "<div>";
foreach($starters as $player)
{
  DisplayStarter($player);
}
echo "</div>";

echo "<h3>Schedule</h3>";

echo "<table class=\"table\"><tbody>";

foreach($games as $game)
{
  DisplayScheduleRow($game, $teamid);
}

echo "<tbody></table>";
  
PrintFooter();
  
function DisplayScheduleRow($game, $teamid)
{
    $rowHighlight = "";
    if($game->homescore != null)
    {
        if(($game->homescore > $game->awayscore && $teamid == $game->home_team->id ) ||
            ($game->homescore < $game->awayscore && $teamid == $game->away_team->id ) )
            {
                $rowHighlight = "success";
            }
            else
            {
                $rowHighlight = "danger";
            }
    }
    echo "<tr class=\"".$rowHighlight."\">";
    $displayDate = new DateTime($game->date);
    echo "<td class=\"col-md-4\"> ". $displayDate->format('m/d H:i') . "</a></td>";
    $opponentName = ($game->away_team->name);
    if($teamid == $game->away_team->id)
    {
        $opponentName = "@ ".$opponentName;
    }
    echo "<td class=\"col-md-4\"><a href=\"team_overview.php?id=".($game->away_team->id) . "\">" . $opponentName . "</a></td>";
    if($game->homescore != null)
    {
        echo "<td>".$game->homescore." - ". $game->awayscore."</td>";
    }
    echo "</tr>";
}

function DisplayStarter($player)
{
    $labelAppearance = "default";
    if($player->overall > 60)
    {
        $labelAppearance = "success"; //green!
    }
    echo "<span class=\"label label-".$labelAppearance."\">";
    echo $player->lastname. " " .  $player->pos. " " .  round($player->overall);
    echo "</span>";
}

?>
