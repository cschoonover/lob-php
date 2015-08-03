<?php
class Game{
    public $gameid;
    public $home_team;
    public $away_team;
    public $date;
    public $neutralsiteflag;
    public $homescore;
    public $awayscore;
    
    function __construct($t1, $t2, $date)
    {
        $this->home_team = $t1;
        $this->away_team = $t2;
        $this->date = $date;
    }
    
    //SORT FUNCTIONS
    public static function sortByDate($a, $b)
    {
        $adate = new DateTime($a->date);
        $bdate = new DateTime($b->date);
        var_dump($adate->diff($bdate)->days);
        return $adate->diff($bdate)->days;
    }
    
    public function HasSameTeam($otherGame)
    {
        return ($this->home_team->id == $otherGame->home_team->id) ||
        ($this->home_team->id == $otherGame->away_team->id) ||
        ($this->away_team->id == $otherGame->home_team->id) ||
        ($this->away_team->id == $otherGame->away_team->id);
    }
}
?>