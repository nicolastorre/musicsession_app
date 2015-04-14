<?php

class Pubdate
{
    public static function printDate($pubdate) {
        $now = new DateTime("now");
        $pubdate = new DateTime($pubdate);
        $interval = $now->diff($pubdate);
        if (intval($interval->format("%a")) == 0) {
            if (intval($interval->format("%H")) == 0) {
                return intval($interval->format("%i"))." min ago"; 
            } else {
                return intval($interval->format("%H"))." Hrs ago";   
            }
        } else {
            return intval($interval->format("%a"))." days ago";
        }
    }
}

?>