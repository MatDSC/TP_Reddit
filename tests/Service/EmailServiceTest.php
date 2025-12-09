<?php

namespace App\Tests\Service;

use App\Service\EmailService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailServiceTest extends TestCase
{
    private $mailer;
    private EmailService $emailService;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->emailService = new EmailService($this->mailer);
    }

    public function testSendActivationEmail(): void
    {
        $this->mailer
            ->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($email) {
                return $email instanceof TemplatedEmail
                    && $email->getTo()[0]->getAddress() === 'test@example.com'
                    && str_contains($email->getSubject(), 'Activez votre compte');
            }));

        $this->emailService->sendActivationEmail(
            'test@example.com',
            'testuser',
            'test_token_123'
        );
    }
}



