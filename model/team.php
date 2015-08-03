<?php
class Team{
    public $id;
    public $confid;
    public $name;
    public $wins;
    public $losses;
    // public $primary_color;
    // public $stadium_name;
    // public $stadium_capacity;
    // public $location_name;
    // public $loc_x;
    // public $loc_y;
    
    //SCHEDULING VARS
    public $homeGames;
    public $awayGames;
    
    
    function Team($id_temp, $confid_temp, $name_temp)
    {
        $this->id = $id_temp;
        $this->confid = $confid_temp;
        $this->name = $name_temp;
    }
    
    public function GetWinPct()
    {
        if(($this->wins + $this->losses) == 0)
        {
            return 0.5;
        }
        else
        {
            return (float)$this->wins/($this->wins + $this->losses);
        }
    }
}
?>