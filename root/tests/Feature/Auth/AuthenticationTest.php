<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private const TEST_PASSWORD = "!xDi9089789lko";

    public function test_home_routes_are_protected_from_public(): void
    {
        $response = $this->get('/home');
        $response->assertStatus(302);
        $response->assertRedirect('login');


        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/home');
        $response->assertOk();
    }

    public function test_user_can_login_with_correct_password():void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = self::TEST_PASSWORD),
        ]);
        $params = [
            'email' => $user->email,
            'password' => $password
        ];
        $response = $this->from('/authenticate')->post('/authenticate', $params);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);

    }

    public function test_user_cannot_login_with_incorrect_password():void
    {
        $user = User::factory()->create([
            'password' => bcrypt(self::TEST_PASSWORD),
        ]);

        $response = $this->from('/authenticate')->post('/authenticate', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('/authenticate');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

}