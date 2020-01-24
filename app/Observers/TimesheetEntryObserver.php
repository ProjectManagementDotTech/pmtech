<?php

namespace App\Observers;

use App\Repositories\TimesheetEntryRepository;
use App\TimesheetEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimesheetEntryObserver
{
    //region Public Access

    /**
     * Make sure everything else is fine... If not, abort *hard*.
     *
     * @param TimesheetEntry $timesheetEntry
     */
    public function creating(TimesheetEntry $timesheetEntry)
    {
        $endTime = Carbon::now()->subSecond();
        $user = Auth::user();

        if($user) {
            /*
             * Check to see if there is a still running timesheet entry for the
             * logged in user. If so, close it.
             */
            /* BR000018 */
            $openTimesheetEntries = TimesheetEntryRepository::filter([
                'user_id' => $user->id,
                'ended_at' => NULL
            ]);
            if(count($openTimesheetEntries) > 0) {
                foreach($openTimesheetEntries as $timesheetEntry) {
                    TimesheetEntryRepository::update($timesheetEntry, [
                        'ended_at' => $endTime
                    ]);
                }
            }
        }

        $data = [
            'user_id' => $timesheetEntry->user_id,
            'started_at' => $timesheetEntry->started_at
        ];
        /* BR000017 */
        $overlappingEntries = $this->overlappingEntries($data);
        if(count($overlappingEntries) > 0) {
            abort(422, json_encode([
                'message' => 'There are overlapping timesheet entries.',
                'errors' => [
                    'started_at' => [
                        'There is at least one timesheet entry that overlaps ' .
                        'with this new timesheet entry.'
                    ]
                ],
                'timesheet_entries' => $overlappingEntries
            ]));
        }
    }

    //endregion

    //region Protected Implementation

    /**
     * Retrieve all timesheet entries that overlap with the provided $newData.
     *
     * @param array $newData
     * @return array
     */
    protected function overlappingEntries(array $newData): array
    {
        /*
         * Overlapping entries (BR000017):
         *
         * existing record: |-----|
         * existing record:             |-----|
         * existing record:                         |-----|
         * new record 1:        |----|
         * new record 2:               |-------|
         * new record 3:                              |-|
         * new record 4:                        |-|
         * new record 5: |----|
         *
         * Only new record 4 is good. New record 1, 3 and 5 are easy to detect
         * by looking at their started_at or ended_at attribute only.
         * New record 3 needs finding an existing entry where its started_at and
         * ended_at attribute are surrounded by the new entry
         * started_at > newStartedAt *and* endedAt < newEndedAt
         */

        $startedAtBetween = [];
        $endedAtBetween = [];
        $existingBetween = [];

        $endedAtSet = FALSE;
        $startedAtSet = FALSE;
        if(isset($newData['started_at'])) {
            $startedAtBetween = TimesheetEntryRepository::filter([
                'user_id' => $newData['user_id'],
                'between' => $newData['started_at']
            ])->toArray();
            $startedAtSet = TRUE;
        }
        if(isset($newData['ended_at'])) {
            $endedAtBetween = TimesheetEntryRepository::filter([
                'user_id' => $newData['user_id'],
                'between' => $newData['started_at']
            ])->toArray();
            $endedAtSet = TRUE;
        }
        if($endedAtSet && $startedAtSet) {
            $existingBetween = TimesheetEntryRepository::filter([
                'user_id' => $newData['user_id'],
                'existing_between' => [
                    $newData['started_at'],
                    $newData['ended_at']
                ]
            ])->toArray();
        }

        return array_merge($startedAtBetween, $endedAtBetween,
            $existingBetween);
    }

    //endregion
}
