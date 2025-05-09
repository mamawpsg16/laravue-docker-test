<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'secretqwe',
            'password_confirmation' => 'secretqwe',
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Registration successful!',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }
}
