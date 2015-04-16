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
                    return intval($interval->format("%a")).Translator::translate("d");
                }
            } else {
                return intval($interval->format("%n")).Translator::translate("m");
            }
        } else {
            return intval($interval->format("%y")).Translator::translate("y");
        }
    }
}

?>