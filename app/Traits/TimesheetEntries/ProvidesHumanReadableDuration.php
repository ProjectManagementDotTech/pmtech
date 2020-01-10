<?php

namespace App\Traits\TimesheetEntries;

use App\Traits\Time\ConvertsSecondsToHumanReadableDuration;

trait ProvidesHumanReadableDuration
{
    use ConvertsSecondsToHumanReadableDuration;

    //region Protected Implementation

    /**
     * Convert the `total_seconds` on each group of timesheet entries to
     * `duration`, which is a human readable string.
     *
     * @param array $timesheetEntries
     * @return array
     */
    protected function provideHumanReadableDurations(array $timesheetEntries):
        array
    {
        $count = count($timesheetEntries);
        for($i = 0; $i < $count; $i++) {
            $timesheetEntries[$i]['duration'] =
                $this->convertSecondsToHumanReadableDuration(
                    $timesheetEntries[$i]['total_seconds']
                );
        }

        return $timesheetEntries;
    }

    //endregion
}
