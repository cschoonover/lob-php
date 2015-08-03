<?php
class Player{
     public $playerid;
     public $jersey_no; //varchar(10),
     public $firstname; //varchar(50),
     public $lastname; //varchar(50),
     public $age; //int,
     public $pos; //varchar(10),
     public $ht; //int,
     public $wt; //int,
     public $class_standing; //int,
     public $redshirt; //BOOL,
     public $hometown;
    
    //COMPUTED
    public $overall;
    
    function __construct($playerid)
    {
        $this->playerid = $playerid;
    }
}
?>