<?php
namespace App\Service;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer) { $this->mailer = $mailer; }

    public function sendValidationEmail(string $to, string $username, string $token): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@reddit.com')
            ->to($to)
            ->subject('Activez votre compte - Reddit')
            ->htmlTemplate('email/welcome.html.twig')
            ->context([
                'username' => $username,
                'token' => $token
            ]);

        $this->mailer->send($email);
    }
}
