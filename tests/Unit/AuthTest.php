<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;

class AuthTest extends TestCase
{
    use DatabaseTransactions;


    public function test_registration_success()
    {
        $testData = [
            'email' => 'backend@multisyscorp.com',
            'password' => Hash::make('test123')
        ];
        
        $response = $this->call('POST', '/api/register', $testData);
        $response->assertStatus(201)->assertJson([
            'message' => 'User successfully registered',
        ]);
    }

    public function test_registration_email_exists()
    {
        $testData = [
            'email' => 'backend@multisyscorp.com',
            'password' => Hash::make('test123')
        ];

        User::create($testData);
        
        $response = $this->call('POST', '/api/register', $testData);
        $response->assertStatus(400)->assertJson([
            'message' => 'Email already taken',
        ]);
    }

    public function test_login_valid_credentials()
    {
        $credentials = [
            'email' => 'backend@multisyscorp.com',
            'password' => Hash::make('test123')
        ];
        User::create($credentials);

        $testData = [
            'email' => 'backend@multisyscorp.com',
            'password' => 'test123'
        ];

        $response = $this->call('POST', '/api/login', $testData);
        $response->assertStatus(201)
            ->assertJsonStructure(['access_token']);
    }

    public function test_login_invalid_credentials()
    {
        $credentials = [
            'email' => 'backend@multisyscorp.com',
            'password' => Hash::make('test123')
        ];
        User::create($credentials);

        $testData = [
            'email' => 'backend@multisyscorp.com',
            'password' => 'test143'
        ];

        $response = $this->call('POST', '/api/login', $testData);
        $response->assertStatus(401)->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }
}
