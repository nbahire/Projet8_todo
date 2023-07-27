<?php

namespace App\Tests\UnitTests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        $this->user = new User();
    }
    public function testId(): void
    {
        $this->assertNull($this->user->getId());
    }

    public function testName(): void
    {
        $this->user->setUsername('test');
        $this->assertSame('test', $this->user->getUsername());

    }
    public function testPassword(): void
    {
        $this->user->setPassword('pwd');
        $this->assertSame('pwd', $this->user->getPassword());

    }

    public function testEmail(): void
    {
        $this->user->setEmail('test@test.com');
        $this->assertSame('test@test.com', $this->user->getEmail());

    }

    public function testRoles(): void
    {
        $this->user->setRole('ROLE_USER');
        $this->assertSame('ROLE_USER', $this->user->getRole());

    }
}
