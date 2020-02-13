<?php

namespace App\Traits\TimesheetEntries;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

trait GroupsTimesheetEntriesByDate
{
    /**
     * Convert the collection of timesheet entries to an array grouped and
     * indexed by the date of each timesheet entry's ended_at timestamp.
     *
     * @param Collection $timesheetEntries
     * @return array
     */
    protected function groupTimesheetEntriesByDate(
        Collection $timesheetEntries): array
    {
        $result = [];
        $resultIndex = -1;
        $previousDate = '';

        foreach($timesheetEntries as $timesheetEntry) {
            if(!$timesheetEntry->ended_at) {
                /*
                 * We do not take open-ended timesheet entries into account...
                 */
                continue;
            }
            try {
                $indexDate = new Carbon($timesheetEntry->ended_at);
            } catch(\Exception $e) {
                continue;
            }
            $indexDate->startOfDay();
            $indexDateString = $indexDate->toDateString();
            if($indexDateString !== $previousDate) {
                $resultIndex++;
                $previousDate = $indexDateString;
                $result[$resultIndex] = [
                    'entries' => [],
                    'date' => $indexDateString,
                    'total_seconds' => 0,
                ];
            }
            $result[$resultIndex]['entries'][] = $timesheetEntry;
            $result[$resultIndex]['total_seconds'] +=
                $timesheetEntry->total_seconds;
        }

        return $result;
    }
}
