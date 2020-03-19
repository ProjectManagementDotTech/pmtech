<?php

namespace Tests\Cases\BusinessRequirements;

use App\Mail\AccountActivation;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\Shared\TestCase;

class BR000002_AllowAccountActivationEmailToBeRegeneratedUponUserRequestTest
    extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function regenerateAccountActivationEmail()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $this->post('/register', [
            'name' => 'Business Requirement 000002',
            'email' => 'br000002@test.com',
            'password' => 'Welcome123',
            'password_confirmation' => 'Welcome123'
        ]);

        $firstCacheValue = Cache::store('database')->get('br000002@test.com');

        $response = $this->post('/api/v1/verification/resend', [
            'email' => 'br000002@test.com'
        ]);

        $response->assertStatus(201);
        $secondCacheValue = Cache::store('database')->get('br000002@test.com');
        $user = $this->userRepository->findByEmail('br000002@test.com');
        $this->assertNotEquals($secondCacheValue, $firstCacheValue);
        Mail::assertSent(AccountActivation::class,
            function ($mail) use ($user) {
                return $mail->user()->id == $user->id;
            }
        );
    }

    /** @test */
    public function regenerateAccountActivationEmailAfterEmailVerified()
    {
        Log::info(__METHOD__);

        $response = $this->post('/api/v1/verification/resend', [
            'email' => 'br000001@test.com'
        ]);

        $response->assertStatus(422);
    }

    /**
     * @var UserRepository
     */
    protected $userRepository;
}
