<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender
{
    public function __construct(private MailerInterface $mailer) {}

    public function send(string $recipient, string $subject, string $body): void
    {
        $email = (new Email())
            ->from('noreply@example.com')
            ->to($recipient)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}
