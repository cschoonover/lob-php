<?php

function CreateConnection()
{
    date_default_timezone_set('UTC');
    
    
    $connectstring = getenv('IP');
    $C9_USER = getenv('C9_USER');
    $con=mysqli_connect($connectstring,$C9_USER,'','c9');
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    return $con;
}

?>