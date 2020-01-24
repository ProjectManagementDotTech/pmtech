<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Repositories\TimesheetEntryRepository;
use App\TimesheetEntry;
use App\Traits\TimesheetEntries\GroupsTimesheetEntriesByDate;
use App\Traits\TimesheetEntries\ProvidesHumanReadableDuration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimesheetEntryController extends Controller
{
    use GroupsTimesheetEntriesByDate, ProvidesHumanReadableDuration;

    //region Public Status Report

    /**
     * Create a new timesheet entry based on the input in the request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        /* BR000019 */
        $this->authorize('create', TimesheetEntry::class);

        $startTime = Carbon::now();
        $endTime = Carbon::now()->subSecond();
        $user = Auth::user();

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

        $data = $request->input();
        if(!isset($data['started_at'])) {
            $data['started_at'] = $startTime;
        }
        $data['user_id'] = $user->id;
        if($data['description'] == NULL) {
            $data['description'] = '';
        }

        /* BR000017 */
        $overlappingEntries = $this->overlappingEntries($data);
        if(!count($overlappingEntries)) {
            $timesheetEntry = TimesheetEntryRepository::create($data);

            return response('', 201, [
                'Location' => route('timesheet_entries.show', [
                    'timesheetEntry' => $timesheetEntry->id
                ])
            ]);
        } else {
            return response([
                'message' => 'There are overlapping timesheet entries.',
                'errors' => [
                    'started_at' => [
                        'There is at least one timesheet entry that overlaps ' .
                        'with this new timesheet entry.'
                    ]
                ],
                'timesheet_entries' => $overlappingEntries
            ], 422);
        }
    }

    public function index(Request $request)
    {
        $endDate = $request->get('end_date', NULL);
        $startDate = $request->get('start_date', NULL);
        $user = Auth::user();

        if($endDate && $startDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)
                ->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)
                ->endOfDay();
        } else {
            $numberOfDays = $request->get('number_of_days', 5);
            $startDate = Carbon::now()
                ->startOfDay()
                ->subWeekdays($numberOfDays)
                ->addWeekday();
            $endDate = Carbon::now()->endOfDay();
        }
        $filterData = [
            'user_id' => $user->id,
            'started_at' => $startDate,
            'ended_at' => $endDate
        ];
        /*
         * Assignment in if expression! Evaluates to the assigned value... So if
         * `$request->input(...)` returns NULL, `$projectId` is not added to
         * `$filterData`.
         * Same for `$taskId`.
         * -- glj
         */
        if($projectId = $request->input('project_id', NULL)) {
            $filterData['project_id'] = $projectId;
        }
        if($taskId = $request->input('task_id', NULL)) {
            $filterData['task_id'] = $taskId;
        }

        $timesheetEntries = TimesheetEntryRepository::filter($filterData);

        return $this->provideHumanReadableDurations(
            $this->groupTimesheetEntriesByDate($timesheetEntries));
    }

    /**
     * Show the given timesheet entry.
     *
     * @param TimesheetEntry $timesheetEntry
     * @return TimesheetEntry
     */
    public function show(TimesheetEntry $timesheetEntry)
    {
        return $timesheetEntry;
    }

    /**
     * Show the running timesheet entry (if there is one).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function showRunning()
    {
        return TimesheetEntryRepository::filter([
            'user_id' => Auth::user()->id,
            'ended_at' => NULL
        ]);
    }

    public function update(Request $request, TimesheetEntry $timesheetEntry)
    {
        $data = $request->input();
        /*
         * Remove 'description', if the input is, in fact, NULL...
         */
        if($data['description'] == NULL) {
            unset($data['description']);
        }

        TimesheetEntryRepository::update($timesheetEntry, $data);

        return response('', 204);
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
