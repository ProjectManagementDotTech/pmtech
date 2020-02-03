<?php

namespace Tests\Shared;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

class TestCase extends BaseTestCase
{
    //region Public Access

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    //endregion

	//region Public Status Report

	/**
	 * Login with $email and $password.
	 *
	 * @param string $email
	 * @param string $password
	 * @return void
	 */
	public function login(string $email, string $password)
	{
	    $cookieResponse = $this->get('/airlock/csrf-cookie');
        $cookieResponse->assertCookie('XSRF-TOKEN');

		$response = $this->post('/api/v1/login', [
			'email' => $email,
			'password' => $password,
            '_token' => $cookieResponse->headers->getCookies()[0]->getValue()
		]);
		$response->assertStatus(302);
	}

    /**
     * Log the user out.
     */
	public function logout()
    {
        Auth::logout();
    }

    //region Protected Attributes

    /**
     * Additional headers for the request.
     *
     * @var array
     */
    protected $defaultHeaders = [
        'Accept' => 'application/json'
    ];

    //endregion
}
