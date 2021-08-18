<?php


namespace DigiHelfer\EspTBundle\Helpers;

use DateTime;

class DateUtils {

    /**
     * adapted from https://stackoverflow.com/a/14203947/5589264
     * @param $start_one
     * @param $end_one
     * @param $start_two
     * @param $end_two
     * @return int overlap in minutes
     */
    public static function datesOverlap(DateTime $start_one, DateTime $end_one, DateTime $start_two, DateTime $end_two): int {
        if ($start_one <= $end_two && $end_one >= $start_two) { //If the dates overlap
            return min($end_one, $end_two)->diff(max($start_two, $start_one))->minutes + 1; //return how many days overlap
        }
        return 0; //no overlap
    }

}