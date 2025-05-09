<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase; // Use Laravel's base TestCase class
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    /** @test */
    public function it_can_create_a_user()
    {
        // Arrange
        $name = 'John Doe';
        $email = 'john@example.com';
        $password = 'password123';

        // Act
        $user = User::createUser($name, $email, $password);

        // Assert
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
        $this->assertNotNull($user);
        $this->assertTrue(password_verify($password, $user->password)); // Check if password is hashed
    }
}
