<?php
function PrintHeader($title){
    
echo "<!DOCTYPE html>
<html>
  <head>
    <title>$title</title>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <!-- Bootstrap -->
    <link href=\"bootstrap303/css/bootstrap.min.css\" rel=\"stylesheet\">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src=\"https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js\"></script>
      <script src=\"https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js\"></script>
    <![endif]-->
    <style>
    body
    {
        padding-top: 50px;
    }
    </style>
  </head>
  <body> ";
}

function PrintFooter()
{
        echo "<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src=\"https://code.jquery.com/jquery.js\"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src=\"bootstrap303/js/bootstrap.min.js\"></script>
      </body>
    </html>";
}
?>