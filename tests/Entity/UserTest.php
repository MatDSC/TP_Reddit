<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntity(): void
    {
        $user = new User();

        $user->setEmail('testunitaire@gmail.com');
        $user->setUsername('testunitaire');
        $user->setPassword('motdepasse');
        $user->setIsConfirmed(true);

        $this->assertEquals('testunitaire@gmail.com', $user->getEmail());
        $this->assertEquals('testunitaire', $user->getUsername());
        $this->assertEquals('motdepasse', $user->getPassword());
        $this->assertTrue($user->isConfirmed());
        $this->assertEquals('testunitaire@gmail.com', $user->getUserIdentifier());
    }

    public function testUserRoles(): void
    {
        $user = new User();

        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testActivationToken(): void
    {
        $user = new User();
        $token = 'token_001';

        $user->setActivationToken($token);
        $this->assertEquals($token, $user->getActivationToken());

        $user->setActivationToken(null);
        $this->assertNull($user->getActivationToken());
    }

    public function testTokenExpiration(): void
    {
        $user = new User();

        // Test expired token
        $pastDate = new \DateTime('-1 hour');
        $user->setTokenExpiresAt($pastDate);
        $this->assertTrue($user->isTokenExpired());

        // Test valid token
        $futureDate = new \DateTime('+1 hour');
        $user->setTokenExpiresAt($futureDate);
        $this->assertFalse($user->isTokenExpired());
    }

    public function testResetToken(): void
    {
        $user = new User();
        $resetToken = 'token_002';

        $user->setResetToken($resetToken);
        $this->assertEquals($resetToken, $user->getResetToken());

        $expiresAt = new \DateTime('+1 hour');
        $user->setResetTokenExpiresAt($expiresAt);
        $this->assertEquals($expiresAt, $user->getResetTokenExpiresAt());
        $this->assertFalse($user->isResetTokenExpired());
    }
}
