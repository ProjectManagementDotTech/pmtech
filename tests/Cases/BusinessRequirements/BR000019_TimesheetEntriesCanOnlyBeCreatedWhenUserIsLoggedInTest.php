<?php

namespace Tests\Cases\BusinessRequirements;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000019_TimesheetEntriesCanOnlyBeCreatedWhenUserIsLoggedInTest extends TestCase
{
    /** @test */
    public function createTimesheetEntryWhenNotLoggedIn()
    {
        Log::info(__METHOD__);

        Auth::logout();

        $response = $this->post('/api/v1/timesheet_entries', [
            'started_at' => Carbon::now()->addSeconds(2),
            'description' => 'BR000019-0001'
        ]);
        $response->assertStatus(401)->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }
}
