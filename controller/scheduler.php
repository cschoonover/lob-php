<?php
require_once('../model/team.php');
require_once('../model/game.php');
require_once('DBConnection.php');
require_once('util.php');

//$POSSIBLE_TIMES = array("12:00","13:30","19:00","20:00","21:00");

function CreateMatchupsSchedule()
{
    date_default_timezone_set('UTC');
    $con = CreateConnection();
    //Get all the teams
    $teams = array();
    $result = mysqli_query($con,"SELECT * FROM team_tbl t
    inner join conference_to_team_tbl ct
    on t.teamid = ct.teamid;");
    while($row = mysqli_fetch_array($result))
    {
        $team = new Team($row['teamid'], $row['conferenceid'], $row['name']);
        $team->homeGames = 0;
        $team->awayGames = 0;
        $teams[] = $team;
    }
    
    $matchups = array();
    
    $teamct = count($teams);
    
    ///CREATE ALL THE MATCHUPS
    for($i=0; $i<$teamct; $i++)
    {
        for($j=0; $j<$teamct; $j++)
        {
            if($i!=$j)
            {
                if($teams[$i]->confid == $teams[$j]->confid)
                {
                    $matchups[$teams[$i]->confid][] = new Game($teams[$i], $teams[$j], null);
                    $teams[$i]->homeGames++;
                    $teams[$j]->awayGames++;
                }
            }
        }
    }
    
    $gamesByDateByConference = array();
    
    foreach($matchups as $confMatchups)
    {
        $gamesByDate = array_pad(array(), 40, array());
        shuffle($confMatchups);
        foreach($confMatchups as $game)
        {
            for($i=0; $i<count($gamesByDate); $i++)
            {
                $containsTeam = false;
                for($j=0; $j < count($gamesByDate[$i]); $j++)
                {
                    if($gamesByDate[$i][$j]->HasSameTeam($game))
                    {
                        $containsTeam = true;
                    }
                }
                if(!$containsTeam)
                {
                    $gamesByDate[$i][] = $game;
                    $i = count($gamesByDate); // hack -- break?
                }
            }
        }
        //var_dump($gamesByDate);
        $gamesByDateByConference[] = $gamesByDate;
    }
    
    foreach($gamesByDateByConference as $conferenceMatchups)
    {
        $i = 0;
        foreach($conferenceMatchups as $dailyGames)
        {
            $date = new DateTime();
            $date->setDate(GetSeasonYear(), 4, 1);
            $daysFromLastGame = new DateInterval('P1D');
            $daysFromLastGame->d = $i;
            $date->sub($daysFromLastGame);
            foreach($dailyGames as $game)
            {
                $homeid = $game->home_team->id;
                $awayid = $game->away_team->id;
                $date->setTime(rand(18,21),0);
                $query = "INSERT INTO game_tbl (hometeamid, awayteamid, date, confmatchupflag) 
                    values ('".$homeid."','".$awayid."','".$date->format('Y-m-d H:i:s')."', '1');";
                $result = mysqli_query($con,$query);
                if (!$result) {
                    echo $query;
                    die('Invalid query: ' . mysql_error());
                }
            }
            $i++;
        }
        
    }

    mysqli_close($con);
    
    ResetDateCounter();
    
    echo '<b>Schedule Creation Completed Successfully!</b><br>' . (time());
}

function GetSeasonYear()
{
    return 2014;
}

function CreateSchedule()
{
    date_default_timezone_set('UTC');
 
    $con = CreateConnection();
    
    //Get all the teams
    $teams = array();
    $result = mysqli_query($con,"SELECT * FROM team_tbl t
    inner join conference_to_team_tbl ct
    on t.teamid = ct.teamid;");
    while($row = mysqli_fetch_array($result))
    {
         $team = new Team($row['teamid'], $row['conferenceid'], $row['name']);
         $teams[] = array($team, 30);     //      //TODO Magic Number
    }
    
    $conference_result = mysqli_query($con,"SELECT * FROM conference_tbl");
    
    $conferences = array();
    while($row = mysqli_fetch_array($conference_result))
    {
         $conferences[] = array($row['conferenceid'], null);
    }

    $num_conferences = count($conferences);
    
    for($i=0; $i<$num_conferences; $i++)
    {
        $conf_id = $conferences[$i][0];
        $conf_teams = array_filter($teams, 
            function ($team) use ($conf_id) 
            { 
                return ($team[0]->confid == $conf_id);
            }
        );
        $conferences[$i][1] = array_values($conf_teams);
    }
    
    $games = ConferenceSchedule($teams,$conferences);
    
    foreach($games as $game)
    {
        //var_dump($game);
        $homeid = $game->home_team->id;
        $awayid = $game->away_team->id;
        $date = $game->date;
        $query = "INSERT INTO game_tbl (hometeamid, awayteamid, date) values ('".$homeid."','".$awayid."','".$date->format('Y-m-d H:i:s')."');";
        $result = mysqli_query($con,$query);
        if (!$result) {
            echo $query;
            die('Invalid query: ' . mysql_error());
        }
    }
    
    
    mysqli_close($con);
    
    
    
    echo '<b>Schedule Creation Completed Successfully!</b><br>' . (time());
}

function ConferenceSchedule($teams, $conferences)
{
    $games = array();
    foreach($conferences as $conference)
    {
        $games = array_merge($games,ScheduleForSingleConference($teams, $conference[1]));
    }
    return $games;
}

function ScheduleForSingleConference($teams, $conference)
{
    $games = array();
    $teams_in_conference = count($conference);
    if($teams_in_conference > 1)
    {
        for($i=0; $i<$teams_in_conference; $i++)
        {
            for($j = $i+1; $j<$teams_in_conference; $j++)
            {
                $games = array_merge($games, CreateHomeAndAway($conference[$i],$conference[$j]));
            }
        }
    }
    return $games;
}

function CreateHomeAndAway($paira, $pairb)
{
    $teama = $paira[0];
    $teamb = $pairb[0];
    $GameDate = new DateTime();
    $daysFromLastGame = new DateInterval('P1D');
    //$daysFromLastGame->d = $j;
    $GameDate->sub($daysFromLastGame);
    $GameDate->setTime(rand(18,21),0);
    $g1 = new Game($teama, $teamb, $GameDate);
    $GameDate = new DateTime();
    //$daysFromLastGame->d = $i;
    $GameDate->sub($daysFromLastGame);
    $GameDate->setTime(rand(18,21),0);
    $g2 = new Game($teamb, $teama, $GameDate);
    return array($g1,$g2);
}


// function SimpleScheduleWithDates($teams)
// {
//     //$DateOfRequest = new DateTime();
//     //var_dump($DateOfRequest);
//     //$LastGameDate = CalcualteDateOfNCAAChampGame($DateOfRequest->format("Y"));
//     $games = array();
//     while(!empty($teams) && $teams[0][1] > 0)
//     {
//         $opponent_key = array_rand($teams);
//         if($opponent_key == 0)
//         {
//             $opponent_key = 1;
//         }
//         // $GameDate = $LastGameDate;
//         // //this interval stuff is just to subtract x amount of days
//         // $interval = new DateInterval('P'.$teams[0][1].'D');
//         // $interval->invert = 1;
//         // var_dump($interval);
//         // var_dump($GameDate);
//         // $GameDate->add($interval);
//         $GameDate = new DateTime();
//         $daysFromLastGame = new DateInterval('P1D');
//         $daysFromLastGame->d = $teams[0][1];
//         $GameDate->sub($daysFromLastGame);
//         $games[] = new Game($teams[0][0], $teams[$opponent_key][0], $GameDate);
//         $teams[0][1]--;
//         $teams[$opponent_key][1]--;
//         if($teams[0][1] < 1)
//         {
//             array_splice($teams, 0, 1);
//             $opponent_key --;
//         }
//         if($teams[$opponent_key][1] < 1)
//         {
//             array_splice($teams, $opponent_key, 1);
//         }
//     }
//     return $games;
// }

//     //iterate through the array. while the current team has games remaining
//     //select another team at random from the remaining teams, increment both
//     //of the teams' game counts, create a game, and add it to the game array
//     //check to see if either team has no more games remaining. if so, remove 
//     //from the array

// function SimpleSchedule($teams)
// {
//     $DateOfRequest = date("Y-m-d H:i:s");
//     $games = array();
//     while(!empty($teams) && $teams[0][1] > 0)
//     {
//         $opponent_key = array_rand($teams);
//         if($opponent_key == 0)
//         {
//             $opponent_key = 1;
//         }
//         $games[] = new Game($teams[0][0], $teams[$opponent_key][0], $DateOfRequest);
//         $teams[0][1]--;
//         $teams[$opponent_key][1]--;
//         if($teams[0][1] < 1)
//         {
//             array_splice($teams, 0, 1);
//             $opponent_key --;
//         }
//         if($teams[$opponent_key][1] < 1)
//         {
//             array_splice($teams, $opponent_key, 1);
//         }
//     }
//     return $games;
// }

//TODO
function CalcualteDateOfNCAAChampGame($currentYear)
{
    $temp = new DateTime();
    $temp->setDate($currentYear, 4, 1);
    return $temp;
}

?>