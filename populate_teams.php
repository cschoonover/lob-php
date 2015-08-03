<?php
$connectstring = getenv('IP');
$C9_USER = getenv('C9_USER');


$con=mysqli_connect($connectstring,$C9_USER,'','c9');
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  //clear the teams table
  mysqli_query($con,"Delete from teams where 1=1");
 
 
 //populate the teams table 
$row = 1;
if (($handle = fopen("teams.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $row++;
        $escaped_team_name = mysqli_real_escape_string($con,$data[0]);

        $query = "INSERT INTO teams (name) values ('".$escaped_team_name."');";
        echo $query;
                echo "<br/>";
        $result = mysqli_query($con,$query);
        echo "<br/>";
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }
    }
    fclose($handle);
}

mysqli_close($con);
  

?>
