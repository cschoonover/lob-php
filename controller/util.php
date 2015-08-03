<?php
require_once('DBConnection.php');

function ResetDateCounter()
{
    $con = CreateConnection();
    $result = mysqli_query($con,"update date_counter_tbl set simdate = (select date(min(date)) from game_tbl);");
    mysqli_close($con);
}

function ImportPlayers()
{
    $con = CreateConnection();
    $result = mysqli_query($con,"select *
    from player_tbl p 
    inner join espn_player_stats_tbl ps
    on p.espn_id = ps.playerid");
    $rows = "";
    
    while($row = mysqli_fetch_array($result))
    {
      $rows = $rows.InsertAttributeRow($row);
    }
    

    
    mysqli_close($con);
    echo '<b>Players Imported!</b><br>' . (time());
}

    // p_str INT,
    // p_end INT,
    // p_jmp INT,
    // p_spd INT,
    // p_vis INT,
    
    // s_dnk INT,
    // s_cls INT,
    // s_lng INT,
    // s_ft INT,
    // s_qr INT,
    // s_usd INT,
    
    // b_pm INT,
    // b_blk INT,
    // b_stl INT,
    // b_reb INT,
    // b_idf INT,
    // b_pdf INT,
    // b_hlp INT,
    // b_ps INT,
    // b_drb INT,
    // b_mvt INT,
    
    // m_tgh INT,
    // m_mrl INT,
    // m_tmw INT,
    // m_acd INT,
    // m_iq INT,

function InsertAttributeRow($row)
{
    $insertval = "INSERT INTO player_ratings_tbl VALUES";
    
    $ppg_percentile = getPercentile($row['ppg'], 5.872, 5.021);
    $rpg_percentile = getPercentile($row['rpg'], 2.661, 2.107);
    $apg_percentile = getPercentile($row['apg'], 1.031, 1.184);
    
    $allstats = $ppg_percentile + $rpg_percentile + $apg_percentile;
    
    $ht_percentile = getPercentile($row['ht'], 75.6963, 4.6125);
    $wt_percentile = getPercentile($row['wt'], 202.9919, 25.8808);
    
    $p_str = floor((($wt_percentile * 4) + $rpg_percentile)/5.2);
    $p_end = floor((((100-$wt_percentile) * 7) + $allstats)/10.2);
    $p_jmp = floor(($rpg_percentile + mt_rand(0,99))/2.1);
    $p_spd = floor(($allstats + (100 -$wt_percentile) + mt_rand(0,99))/5.2);
    $p_vis = floor(($apg_percentile * 4 + mt_rand(0,99)) / 5.2);
    
    $s_dnk = floor(($ppg_percentile + $ht_percentile) / 2.05);
    $s_cls = floor($ppg_percentile);
    $s_lng = floor(($ppg_percentile + mt_rand(0,19)) / 1.2);
    $s_ft = floor($ppg_percentile);
    $s_qr = floor(($ppg_percentile + mt_rand(0,999)) / 11);
    $s_usd = floor(($ppg_percentile + mt_rand(0,999)) / 11);
    
    $b_pm = floor(($ppg_percentile + $ht_percentile + $rpg_percentile) / 4);
    $b_blk = floor($ht_percentile/1.2);
    $b_stl = floor($apg_percentile/1.3);
    $b_reb = floor($ppg_percentile/1.1);
    $b_idf = floor(($rpg_percentile + $ht_percentile) / 2.3);
    $b_pdf = floor($p_spd / 1.2);
    $b_hlp = floor($b_pdf + mt_rand(-5,5));
    $b_ps = floor($apg_percentile/1.1);
    $b_drb = floor($b_stl/1.3 + mt_rand(-5,5));
    $b_mvt = floor($p_spd/1.2 + mt_rand(-5,5));
    
    $m_tgh = floor(mt_rand(40,60));
    $m_mrl = floor(mt_rand(40,60));
    $m_tmw = floor(mt_rand(40,60));
    $m_acd = floor(mt_rand(40,60));
    $m_iq = floor(mt_rand(40,60));
    
    $insertval = $insertval. "('".$row[0]."','".
    $p_str."','".
    $p_end."','".
    $p_jmp."','".
    $p_spd."','".
    $p_vis."','".
    
    $s_dnk."','".
    $s_cls."','".
    $s_lng."','".
    $s_ft."','".
    $s_qr."','".    
    $s_usd."','".
    
    $b_pm."','".
    $b_blk."','".
    $b_stl."','".
    $b_reb."','".
    $b_idf."','".
    $b_pdf."','".
    $b_hlp."','".
    $b_ps."','".
    $b_drb."','".
    $b_mvt."','".
    
    $m_tgh."','".
    $m_mrl."','".
    $m_tmw."','".
    $m_acd."','".
    $m_iq."');";
    
    $con = CreateConnection();
    $insertResult = mysqli_query($con,$insertval);
    
    if (!$insertResult) {
        var_dump($insertval);
    }
    mysqli_close($con);
}

function getPercentile($sample, $avg, $stddev)
{
    $zscore = ($sample - $avg) / $stddev; 
    return cdf($zscore) * 100;
}

function erf($x) 
{ 
        $pi = 3.1415927; 
        $a = (8*($pi - 3))/(3*$pi*(4 - $pi)); 
        $x2 = $x * $x; 

        $ax2 = $a * $x2; 
        $num = (4/$pi) + $ax2; 
        $denom = 1 + $ax2; 

        $inner = (-$x2)*$num/$denom; 
        $erf2 = 1 - exp($inner); 

        return sqrt($erf2); 
} 

function cdf($n) 
{ 
        if($n < 0) 
        { 
                return (1 - erf($n / sqrt(2)))/2; 
        } 
        else 
        { 
                return (1 + erf($n / sqrt(2)))/2; 
        } 
}

function printzscore($sample)
{
    $avg = 5.87; 
    $stddev = 5.02; 
    $zscore = ($sample - $avg) / $stddev; 
    print 'Percentile: ' . cdf($zscore) * 100 . "\n"; 
}

?>