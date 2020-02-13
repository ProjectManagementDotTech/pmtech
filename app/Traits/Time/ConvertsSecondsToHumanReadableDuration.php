<?php

namespace App\Traits\Time;

trait ConvertsSecondsToHumanReadableDuration
{
    //region Protected Implementation

    protected function convertSecondsToHumanReadableDuration(int $seconds):
        string
    {
        $remainder = $seconds;
        $days = ($remainder - $remainder % 86400) / 86400;
        $remainder -= ($days * 86400);
        $hours = ($remainder - $remainder % 3600) / 3600;
        $remainder -= ($hours * 3600);
        $minutes = ($remainder - $remainder % 60) / 60;
        $remainder -= ($minutes * 60);

        return ($days > 0 ? $days . 'd ' : '') .
            str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' .
            str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' .
            str_pad($remainder, 2, '0', STR_PAD_LEFT);
    }

    //endregion
}
