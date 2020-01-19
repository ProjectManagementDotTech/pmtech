<?php

namespace Tests\Shared;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

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
	 * @return array
	 */
	public function login(string $email, string $password): array
	{
	    $cookieResponse = $this->get('/airlock/csrf-cookie');
        $cookieResponse->assertCookie('XSRF-TOKEN');

		$response = $this->post('/api/v1/login', [
			'email' => $email,
			'password' => $password,
            '_token' => $cookieResponse->headers->getCookies()[0]->getValue()
		]);
		if($response->getStatusCode() !== 200) {
//            dd([$response->getContent(), $response->getStatusCode(), $email]);
        }
		$response->assertStatus(200);

		/* NOTREACHED in case Status Code !== 200 */

		$responseBody = json_decode($response->getContent());
		return [
			'token' => $responseBody->access_token,
			'type' => $responseBody->token_type,
		];
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
