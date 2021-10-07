<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_order_success()
    {
        $credentials = [
            'email' => 'backend@multisyscorp.com',
            'password' => Hash::make('test123')
        ];
        $user = User::create($credentials);

        $testData = [
            'product_id' => '1',
            'quantity' => '2'
        ];
        $response = $this->actingAs($user, 'sanctum')
            ->call('POST', '/api/order', $testData);
        $response->assertStatus(201)->assertJson([
            'message' => 'You have successfully ordered this product.',
        ]);
    }

    public function test_order_unsuccessful()
    {
        $credentials = [
            'email' => 'backend@multisyscorp.com',
            'password' => Hash::make('test123')
        ];
        $user = User::create($credentials);

        $testData = [
            'product_id' => '1',
            'quantity' => '9999'
        ];
        $response = $this->actingAs($user, 'sanctum')
            ->call('POST', '/api/order', $testData);
        $response->assertStatus(400)->assertJson([
            'message' => 'Failed to order this product due to unavailability of the stock',
        ]);
    }

}
