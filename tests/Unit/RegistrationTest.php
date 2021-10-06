<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->call('POST', '/login', [
            'email' => 'markjeffreylopez@gmail.com',
            'password' => 'test123'
        ]);

        $this->assertTrue(201, );
    }
}
