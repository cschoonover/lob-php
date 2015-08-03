<?php

require_once('../controller/sim.php');

function PrintMenu($qsid = null, $teamid = null, $confid = null)
{
    if(array_key_exists('advance',$_REQUEST))
    {
        SimOneDay();
    }
    if(array_key_exists('reset',$_REQUEST))
    {
        ResetSchedule();
    }
    
    $displayDate = new DateTime(GetCurrentDate());
    $qs = "?advance=1";
    $conflink = "";
    $teamlink = "";
    $rosterlink = "";
    if($qsid)
    {
        $qs = $qs."&id=".$qsid;
    }
    if($confid)
    {
        $conflink = "<li><a href=\"conference_overview.php?id=".$confid."\">Return to Conference</a></li>";
    }
    if($teamid)
    {
        $teamlink = "<li class=\"active\"><a href=\"team_overview.php?id=".$teamid."\">Team Overview</a></li>";
        $rosterlink = "<li class=\"active\"><a href=\"team_roster.php?id=".$teamid."\">Roster</a></li>";
    }
    echo "<nav class=\"navbar navbar-default navbar-fixed-top\" role=\"navigation\">
            <div class=\"navbar-header\">
    <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\">
      <span class=\"sr-only\">Toggle navigation</span>
      <span class=\"icon-bar\"></span>
      <span class=\"icon-bar\"></span>
      <span class=\"icon-bar\"></span>
    </button>
    <a class=\"navbar-brand\" href=\"index.php\">LOB</a>
  </div>
  <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
    <ul class=\"nav navbar-nav\">".
      $conflink.
      $teamlink.
      $rosterlink.
    "</ul>
      
    <ul class=\"nav navbar-nav navbar-right\">
    <li><a href=\"index.php?reset=1\">Reset Schedule</a></li>
    <li><a href=\"".$qs."\">".$displayDate->format("m/d/y")."</a></li>
    </ul>
      </div><!-- /.navbar-collapse -->
      
</nav>
    ";
}

?>