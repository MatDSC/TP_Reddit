<?php
namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class EmailService
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer) { $this->mailer = $mailer; }
    public function sendValidationEmail(string $to, string $token): void
    {
        $email = (new Email())
            ->from('noreply@redditclone.com')
            ->to($to)
            ->subject('Validate your account')
            ->text("Click here to validate: /validate/$token");
        $this->mailer->send($email);
    }
}
