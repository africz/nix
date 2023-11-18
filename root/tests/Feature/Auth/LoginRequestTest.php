<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;
use App\Http\Requests\LoginRequest;

class LoginRequestTest extends TestCase
{
    use AdditionalAssertions;


    public function test_login_request(): void
    {
        $this->assertActionUsesFormRequest(AuthController::class, 'authenticate', LoginRequest::class);
        $this->assertRouteUsesFormRequest('authenticate',LoginRequest::class);
    }

    public function test_login_rules(): void
    {
        $this->assertValidationRules([
            'email' => 'required|email',
            'password' => 'required'
        ], (new LoginRequest())->rules());
    }

    
}
