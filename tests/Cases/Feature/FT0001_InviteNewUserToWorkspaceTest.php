<?php

namespace Tests\Cases\Feature;

use App\Mail\Invitation;
use App\Repositories\InvitationRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\Shared\TestCase;

class FT0001_InviteNewUserToWorkspaceTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
        $this->workspaceRepository = new WorkspaceRepository(
            $this->userRepository);
    }

    /** @test */
    public function sendInvitation()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $this->login('user0001@test.com', 'Welcome123');

        $workspace = $this->workspaceRepository->findFirstByName('UT0004-0001');
        $this->assertEquals(1, count($workspace->users));

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/invite', [
                'email' => 'user0002@test.com'
            ]
        );
        $response->assertNoContent(201);
        $this->assertDatabaseHas('invitations', [
            'email' => 'user0002@test.com'
        ]);
        Mail::assertSent(Invitation::class, function ($mail) {
            return $mail->invitation()->email == 'user0002@test.com';
        });
    }

    /** @test */
    public function acceptInvitation()
    {
        Log::info(__METHOD__);

        $invitationRepository = new InvitationRepository();
        $invitation = $invitationRepository->findFirstByEmail('user0002@test.com');
        $cacheValue = Cache::store('database')->get('user0002@test.com');
        $response = $this->get('/invitation/accept/' . $invitation->nonce .
            '/' . $cacheValue);
        $response
            ->assertStatus(200)
            ->assertViewIs('app');

        $response = $this->post('/invitation/details/' . $invitation->nonce .
            '/' . $cacheValue, [
                'name' => 'User 0002',
                'password' => 'Welcome123',
                'password_confirmation' => 'Welcome123'
        ]);
        $response->assertStatus(302);
    }

    /** @test */
    public function newUserIsMemberOfWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = $this->workspaceRepository->findFirstByName('UT0004-0001');
        $this->assertEquals(2, count($workspace->users));
        $this->assertEquals(1, $workspace
            ->users()
            ->where('email', 'user0002@test.com')
            ->count()
        );
        $this->assertEquals(1, $workspace
            ->users()
            ->where('email', 'user0001@test.com')
            ->count()
        );
    }

    protected $userRepository;
    protected $workspaceRepository;
}
