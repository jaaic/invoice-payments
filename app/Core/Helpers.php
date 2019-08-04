<?php

namespace App\Core;

class Helpers
{
    /**
     * Gets days difference between 2 dates in same format
     * @param string $date1
     * @param string $date2
     * @return int
     */
    public static function dateDiffInDays(string $date1, string $date2): int
    {
        // Calculating the difference in timestamps
        $diff = strtotime($date2) - strtotime($date1);

        // 1 day = 24 hours
        // 24 * 60 * 60 = 86400 seconds
        return (round($diff / 86400));
    }

}
