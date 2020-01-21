<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MainTest extends TestCase
{

    private $apiPrefix = '/api/v1';

    private function getLogin ()
    {
        $user = DB::table('users')->where('email', 'imanfava@gmail.com')->first();
        Auth::loginUsingId($user->id);
    }

    public function test_bootstrap ()
    {
        # It happens on test database (.env.testing)
        # empty user table
        DB::table('users')->delete();
        $this->assertTrue(true);
    }

    public function test_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_unathorized_user()
    {
        // => redirect to login page and get 302 status
        $response = $this->get('/account');
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function test_do_logining_with_incorrect_credentials ()
    {
        $response = $this->withHeaders([
            ])->json('POST', '/login', [
                'email' => 'incorrect@gmail.com',
                'password' => '12345678'
                ]);
                // These credentials do not match our records.
            $response
                ->assertStatus(422);
    }

    // Register
    public function test_do_registeration ()
    {
        $response = $this->withHeaders([
        ])->json('POST', '/register', [
            'name' => 'Iman Fattahi',
            'email' => 'imanfava@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
            ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/account');
    }

    // Login
    public function test_do_logining ()
    {
        $response = $this->withHeaders([
            ])->json('POST', '/login', [
                'email' => 'imanfava@gmail.com',
                'password' => '12345678'
                ]);
            $response
                ->assertStatus(302)
                ->assertRedirect('/account');
    }

    public function test_has_registerd_email_in_database ()
    {
        $this->assertDatabaseHas('users', [
            'email' => 'imanfava@gmail.com',
        ]);
    }

    // Register user with existing email address
    public function test_duplicate_email_register ()
    {
        $response = $this->withHeaders([
            ])->json('POST', '/register', [
                'name' => 'Iman Fattahi',
                'email' => 'imanfava@gmail.com',
                'password' => '12345678',
                'password_confirmation' => '12345678'
                ]);

                # 422 => The email has already been taken.
            $response
                ->assertStatus(422);
    }

    public function test_unautorized_api_call ()
    {
        $response = $this->withHeaders([
            ])->json('POST', '/api/v1/account/planner/all');
            $response
                ->assertStatus(401);
    }

    public function test_autorized_api_call ()
    {
        # Login user
        $this->getLogin();
        $response = $this->withHeaders([
        ])->json('POST', $this->apiPrefix . '/account/planner/all', []);
        $response
            ->assertStatus(200);
    }
}
