<?php

function HeightDisplayString($heightInt)
{
    $ft = floor($heightInt/12);
    $in = $heightInt%12;
    return $ft."' ".$in."''";
}

?>