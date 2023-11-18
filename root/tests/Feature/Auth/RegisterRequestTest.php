<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;
use App\Http\Requests\RegisterRequest;

class RegisterRequestTest extends TestCase
{
    use AdditionalAssertions;


    public function test_registration_request(): void
    {
        $this->assertActionUsesFormRequest(AuthController::class, 'store', RegisterRequest::class);
        $this->assertRouteUsesFormRequest('store',RegisterRequest::class);
    }

    public function test_registration_rules(): void
    {
        $this->assertValidationRules([
            'name' => 'required|string|first_last_name|max:40',
            'email' => 'required|string|email:rfc,dns|max:40|unique:users,email',
            'password' => 'required|string|min:8|confirmed|strong_password',
            'agreement' => 'required|boolean'
        ], (new RegisterRequest())->rules());
    }
}
