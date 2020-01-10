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
     */
    public function create(Request $request)
    {
        $startTime = Carbon::now();
        $endTime = Carbon::now()->subSecond();
        $user = Auth::user();

        /*
         * Check to see if there is a still running timesheet entry for the
         * logged in user. If so, close it.
         */
        $openTimesheetEntries = TimesheetEntryRepository::filter([
            'user_id' => $user->id,
            'ended_at' => NULL
        ]);
        if($openTimesheetEntries->count() == 1) {
            TimesheetEntryRepository::update($openTimesheetEntries[0], [
                'ended_at' => $endTime
            ]);
        }

        $data = $request->input();
        if(!isset($data['started_at'])) {
            $data['started_at'] = $startTime;
        }
        $data['user_id'] = $user->id;
        if($data['description'] == NULL) {
            $data['description'] = '';
        }
        $timesheetEntry = TimesheetEntryRepository::create($data);

        return response('', 201, [
            'Location' => route('timesheet_entries.show', [
                'timesheetEntry' => $timesheetEntry->id
            ])
        ]);
    }

    public function index(Request $request)
    {
        $endDate = $request->get('end_date', NULL);
        $startDate = $request->get('start_date', NULL);
        $user = Auth::user();

        if($endDate && $startDate) {
            $startDate = Carbon::createFromFormat('d/m/Y', $startDate)
                ->startOfDay();
            $endDate = Carbon::createFromFormat('d/m/Y', $endDate)
                ->endOfDay();
        } else {
            $numberOfDays = $request->get('number_of_days', 5);
            $startDate = Carbon::now()
                ->startOfDay()
                ->subWeekdays($numberOfDays)
                ->addWeekday();
            $endDate = Carbon::now()->endOfDay();
        }
        $timesheetEntries = TimesheetEntryRepository::filter([
            'user_id' => $user->id,
            'started_at' => $startDate,
            'ended_at' => $endDate
        ]);

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
}
