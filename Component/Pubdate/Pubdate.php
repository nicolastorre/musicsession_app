<?php

class Pubdate
{
    public static function printDate($pubdate) {
        $now = new DateTime("now");
        $pubdate = new DateTime($pubdate);
        $interval = $now->diff($pubdate);
        if (intval($interval->format("%y")) == 0) {
            if (intval($interval->format("%n")) == 0) {
                if (intval($interval->format("%a")) == 0) {
                    if (intval($interval->format("%H")) == 0) {
                        return intval($interval->format("%i")).Translator::translate("min"); 
                    } else {
                        return intval($interval->format("%H")).Translator::translate("h");   
                    }
                } else {
                    if (intval($interval->format("%a")) == 1){
                        $day = Translator::translate("ds");
                    } else {
                        $day = Translator::translate("d");
                    }
                    return intval($interval->format("%a")).$day;
                }
            } else {
                if (intval($interval->format("%n")) == 1){
                    $month = Translator::translate("ms");
                } else {
                    $month = Translator::translate("m");
                }
                return intval($interval->format("%n")).$month;
            }
        } else {
            if (intval($interval->format("%y")) == 1){
                $year = Translator::translate("ys");
            } else {
                $year = Translator::translate("y");
            }
            return intval($interval->format("%y")).$year;
        }
    }
}

?>