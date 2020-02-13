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
        if(!$user) {
            $user = $timesheetEntry->user;
        }

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

        $this->abortIfThereAreOverlappingTimesheetEntries($timesheetEntry);
    }

    /**
     * Do some checks before finally storing the timesheet entry.
     *
     * @param TimesheetEntry $timesheetEntry
     */
    public function updating(TimesheetEntry $timesheetEntry)
    {
        $this->abortIfThereAreOverlappingTimesheetEntries($timesheetEntry);
    }

    //endregion

    //region Protected Implementation

    protected function abortIfThereAreOverlappingTimesheetEntries(
        TimesheetEntry $timesheetEntry)
    {
        $data = [
            'id' => $timesheetEntry->id,
            'user_id' => $timesheetEntry->user_id,
            'started_at' => $timesheetEntry->started_at
        ];
        if($timesheetEntry->ended_at !== NULL) {
            $data['ended_at'] = $timesheetEntry->ended_at;
        }

        /* BR000017 */
        $overlappingEntries = $this->overlappingEntries($data);
        if(count($overlappingEntries) > 0) {
            $messageData = [
                'message' => 'There are overlapping timesheet entries.',
                'errors' => [
                    'id' => 'This timesheet entry has id "' .
                        $timesheetEntry->id . '"',
                    'started_at' => [
                        'This timesheet entry started at "' .
                        $timesheetEntry->started_at->format('Y-m-d H:i:s') . '"'
                    ],
                ],
                'timesheet_entries' => $overlappingEntries
            ];
            if($timesheetEntry->ended_at !== NULL) {
                $messageData['errors']['ended_at'] = 'This timesheet entry ' .
                    'ended at "' .
                    $timesheetEntry->ended_at->format('Y-m-d H:i:s') . '"';
            }

            abort(422, json_encode($messageData));
        }
    }

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
                'id' => '<>:' . $newData['id'],
                'user_id' => $newData['user_id'],
                'between' => $newData['started_at']
            ])->toArray();
            $startedAtSet = TRUE;
        }
        if(isset($newData['ended_at'])) {
            $endedAtBetween = TimesheetEntryRepository::filter([
                'id' => '<>:' . $newData['id'],
                'user_id' => $newData['user_id'],
                'between' => $newData['ended_at']
            ])->toArray();
            $endedAtSet = TRUE;
        }
        if($endedAtSet && $startedAtSet) {
            $existingBetween = TimesheetEntryRepository::filter([
                'id' => '<>:' . $newData['id'],
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
