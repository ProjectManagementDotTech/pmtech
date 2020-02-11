<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Repositories\ProjectRepository;
use App\Repositories\TimesheetEntryRepository;
use App\TimesheetEntry;
use App\Traits\TimesheetEntries\GroupsTimesheetEntriesByDate;
use App\Traits\TimesheetEntries\ProvidesHumanReadableDuration;
use App\Workspace;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Ramsey\Uuid\Uuid;

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
        $user = Auth::user();

        $data = $request->input();
        if(!isset($data['started_at'])) {
            $data['started_at'] = $startTime;
        }
        $data['user_id'] = $user->id;
        if($data['description'] == NULL) {
            $data['description'] = '';
        }

        if(isset($data['project_id']) && !isset($data['workspace_id'])) {
            $project = ProjectRepository::find($data['project_id']);
            $data['workspace_id'] = $project->workspace_id;
        }
        $timesheetEntry = TimesheetEntryRepository::create($data);

        return response('', 201, [
            'Location' => route('timesheet_entries.show', [
                'timesheetEntry' => $timesheetEntry->id
            ])
        ]);
    }

    /**
     * Generate an XLS file with the timesheet data, and return a base64 encoded
     * string representing the contents of the file.
     *
     * @param Request $request
     * @return string
     */
    public function export(Workspace $workspace, Request $request)
    {
        $timesheetEntries =
            $this->getTimesheetEntriesBasedOnWorkspaceAndRequest($workspace,
                $request);

        $filename = $this->generateTimesheetExport($timesheetEntries);

        $contents = file_get_contents($filename);
        $base64Encoded = base64_encode($contents);

        unlink($filename);

        return $base64Encoded;
    }

    public function index(Workspace $workspace, Request $request)
    {
        $timesheetEntries =
            $this->getTimesheetEntriesBasedOnWorkspaceAndRequest($workspace,
                $request);

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
     * Store the given timesheet entries in an XLS file, and return the
     * filename.
     *
     * @param Collection $timesheetEntries
     * @return string
     */
    protected function generateTimesheetExport(Collection $timesheetEntries):
        string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Workspace');
        $sheet->setCellValue('B1', 'Project');
        $sheet->setCellValue('C1', 'Task');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('E1', 'Started at');
        $sheet->setCellValue('F1', 'Ended at');
        $sheet->setCellValue('G1', 'Duration');

        $row = 2;
        foreach($timesheetEntries as $timesheetEntry) {
            if($timesheetEntry->workspace)
                $sheet->setCellValue("A$row", $timesheetEntry->workspace->name);
            if($timesheetEntry->project)
                $sheet->setCellValue("B$row", $timesheetEntry->project->name);
            if($timesheetEntry->task)
                $sheet->setCellValue("C$row", $timesheetEntry->task->name);
            $sheet->setCellValue("D$row", $timesheetEntry->description);
            $sheet->setCellValue("E$row",
                $timesheetEntry->started_at->format("d M Y H:i:s"));
            $sheet->setCellValue("F$row",
                $timesheetEntry->ended_at->format("d M Y H:i:s"));
            $sheet->setCellValue("G$row", $timesheetEntry->duration);
            $row++;
        }

        $sheet->setAutoFilter("A1:G$row");

        $fileName = storage_path('app') . '/' . Uuid::uuid4()->getHex() .
            '.xls';
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return $fileName;
    }

    /**
     * Return the timesheet entries requested in the $request.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getTimesheetEntriesBasedOnWorkspaceAndRequest(
        Workspace $workspace, Request $request)
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
            'workspace_id' => $workspace->id,
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

        return TimesheetEntryRepository::filter($filterData);
    }

    //endregion
}
