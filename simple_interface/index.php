<?php
require_once('../controller/DBConnection.php');
require_once('bootstrap.php');
require_once('menu.php');

$con = CreateConnection();
  
$result = mysqli_query($con,"SELECT * FROM conference_tbl");

PrintHeader("Welcome to LOB");
PrintMenu();

echo "<h3>Conferences</h3>";

echo "<div class = \"list-group\">";
while($row = mysqli_fetch_array($result))
  {
  echo "<a href=\"conference_overview.php?id=".$row['conferenceid'] . "\" class=\"list-group-item\">" . $row['name'] . "</a>";
  }
echo "</div>";

mysqli_close($con);
  
PrintFooter();

?>
