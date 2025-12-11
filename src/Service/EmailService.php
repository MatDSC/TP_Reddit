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

    public function sendPostNotification(string $userEmail, string $postTitle): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@reddit.com')
            ->to($userEmail)
            ->subject('Nouveau commentaire sur votre publication - Reddit')
            ->html("<p>Someone commented on your post: <strong>{$postTitle}</strong></p>");
        $this->mailer->send($email);
    }
    public function sendPasswordResetEmail(string $userEmail, string $username, string $token): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@reddit.com')
            ->to($userEmail)
            ->subject('Réinitialisation de votre mot de passe - Reddit')
            ->htmlTemplate('email/password_reset.html.twig')
            ->context([
                'username' => $username,
                'token' => $token,
            ]);
        $this->mailer->send($email);
    }
}
