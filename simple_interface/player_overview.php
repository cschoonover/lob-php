<?php
require_once('../model/team.php');
require_once('../model/game.php');
require_once('../model/player.php');
require_once('../controller/player_queries.php');
require_once('bootstrap.php');
require_once('menu.php');

$playerid = $_REQUEST['id'] ;

$player_info = GetPlayerInfo($playerid);

PrintHeader($player_info['lastname'] . "  -- Player Overview");
PrintMenu();


echo "<h3>".$player_info['firstname']." ".$player_info['lastname']."</h3>";

$ratings = GetPlayerRatings($playerid);

echo "<table class=\"table\"><tbody>";

DisplayOverall($ratings);

DisplayRatings($ratings);


echo "<tbody></table>";

PrintFooter();

function DisplayOverall($row)
{
    $overall = 0;
    foreach($row as $key => $value) {
        if($key != "playerid")
        {
            $overall+=$value;
        }
    }
    $overall = $overall/(count($row) - 1);
    echo "<h4> Overall Rating: ".round($overall)."</h4>";
}

function DisplayRatings($row)
{
    echo "<tr>";  
    foreach($row as $key => $value) {

            echo "<th>". $key."</th>";
    }
    echo "</tr>";
    echo "<tr>";
    foreach($row as $key => $value) {

            echo "<td>". $value."</td>";

    }
    echo "</tr>";
}

// function ovr(ratings) {
//         ///return Math.round((ratings.hgt + ratings.stre + ratings.spd + ratings.jmp + ratings.endu + ratings.ins + ratings.dnk + ratings.ft + ratings.fg + ratings.tp + ratings.blk + ratings.stl + ratings.drb + ratings.pss + ratings.reb) / 15);

//         // This formula is loosely based on linear regression:
//         //     player = require('core/player'); player.regressRatingsPer();
//         return Math.round((4 * ratings.hgt + ratings.stre + 4 * ratings.spd + 2 * ratings.jmp + 3 * ratings.endu + 3 * ratings.ins + 4 * ratings.dnk + ratings.ft + ratings.fg + 2 * ratings.tp + ratings.blk + ratings.stl + ratings.drb + 3 * ratings.pss + ratings.reb) / 32);
//     }

?>
