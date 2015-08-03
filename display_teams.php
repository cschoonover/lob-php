<?php
$connectstring = getenv('IP');
$C9_USER = getenv('C9_USER');
$con=mysqli_connect($connectstring,$C9_USER,'','c9');
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  $result = mysqli_query($con,"SELECT * FROM team_tbl");

echo "<table border='1'>
<tr>
<th>Team ID</th>
<th>Name</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['id'] . "</td>";
  echo "<td><a href=\"teampage.php?id=".$row['id'] . "\">" . $row['name'] . "</a></td>";
  echo "</tr>";
  }
echo "</table>";

mysqli_close($con);
  

?>
