<?php

namespace Tests\Cases\Feature;

use App\Mail\Invitation;
use App\Mail\Payment\FirstTime;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Cashier;
use Tests\Shared\TestCase;

class FT0004_SendEmailWhenPaymentMethodIsNeededTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function setupWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/workspaces', [
            'name' => 'FT0004-0001'
        ]);
        $response->assertStatus(201);
    }

    /** @test */
    public function addFourUsersToWorkspace()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $this->userRepository->create([
            'email' => 'user0003@test.com',
            'email_verified_at' => Carbon::now(),
            'name' => 'Unit Test User 0003',
            'password' => Hash::make('Welcome123')
        ]);

        $workspace = WorkspaceRepository::filter([
            'name' => 'FT0004-0001'
        ])[0];
        $this->login('user0001@test.com', 'Welcome123');

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/invite', [
                'email' => 'user0002@test.com'
            ]
        );
        $response->assertNoContent(201);
        Mail::assertNotSent(FirstTime::class);

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/invite', [
                'email' => 'user0003@test.com'
            ]
        );
        $response->assertNoContent(201);
        Mail::assertNotSent(FirstTime::class);

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/invite', [
                'email' => 'user0004@test.com'
            ]
        );
        $response->assertNoContent(201);
        Mail::assertNotSent(FirstTime::class);

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/invite', [
                'email' => 'user0005@test.com'
            ]
        );
        $response->assertNoContent(201);
        Mail::assertNotSent(FirstTime::class);

        $this->assertEquals(5, $workspace->users()->count());
    }

    /** @test */
    public function addSixthUserToWorkspace()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $this->userRepository->create([
            'email' => 'user0006@test.com',
            'email_verified_at' => Carbon::now(),
            'name' => 'Unit Test User 0006',
            'password' => Hash::make('Welcome123')
        ]);

        $workspace = WorkspaceRepository::filter([
            'name' => 'FT0004-0001'
        ])[0];
        $this->login('user0001@test.com', 'Welcome123');

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/invite', [
                'email' => 'user0006@test.com'
            ]
        );
        $response->assertNoContent(201);
        Mail::assertSent(FirstTime::class, function ($mail) use ($workspace) {
            return $mail->workspace->ownerUser->id == $workspace->owner_user_id;
        });
        $this->assertNotNull($workspace->ownerUser->stripe_id);
    }

    /** @test */
    public function addProjectBeforeSubsscriptionIsActive() {}

    /** @test */
    public function payForTheFirstTime()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');

        $currencyFormatter = new \NumberFormatter('en_IE',
            \NumberFormatter::CURRENCY);

        $workspace = WorkspaceRepository::filter([
            'name' => 'FT0004-0001'
        ])[0];
        $this->assertEquals(6, $workspace->users()->count());
        $ownerUser = $workspace->ownerUser;
        $ownerUser
            ->newSubscription($workspace->id, 'plan_GnxYASr0zyqcFN')
            ->quantity($workspace->users()->count())
            ->create('pm_card_visa');
        $this->assertTrue($ownerUser->subscribed($workspace->id));
        $this->assertEquals(
            $currencyFormatter->formatCurrency(4.99, 'EUR'),
            $ownerUser->invoices()[0]->total()
        );
    }

    protected $userRepository;
}
