<?php
require_once('../model/team.php');
require_once('../model/game.php');
require_once('../controller/DBConnection.php');
require_once('../controller/team_queries.php');
require_once('bootstrap.php');
require_once('menu.php');

$con = CreateConnection();
  
//dirtyyyy TODODO

$conferenceid = $_REQUEST['id'] ;

$teams = array();

PrintHeader("Conference Overview");
PrintMenu($conferenceid);
  
$result = mysqli_query($con,"select * from team_tbl t 
    inner join
conference_to_team_tbl ct
 on t.teamid = ct.teamid
 where ct.conferenceid = " . $conferenceid . ";");

 
 while($row = mysqli_fetch_array($result))
  {
     $team = new Team($row['teamid'],$conferenceid, $row['name']);
     $record = GetRecord($team->id);
     $team->wins = $record['wins'];
     $team->losses = $record['losses'];
     $teams[] = $team;
  }
  
usort($teams, function($a, $b) {
    return $b->GetWinPct() > $a->GetWinPct();
});

mysqli_close($con);
 

echo "<table border='1'>
<tr>
<th>Name</th>
<th>Record</th>
<th>Pct</th>
</tr>";



foreach($teams as $team)
  {
      
      echo "<tr>";
      echo "<td><a href=\"team_overview.php?id=".($team->id) . "\">" . ($team->name) . "</a></td>";
      echo "<td>".$team->wins." - ".$team->losses."</td>";
      echo "<td>".number_format($team->GetWinPct(), 3)."</td>";
      echo "</tr>";
  }
  echo "</table>";
  
PrintFooter();

?>
