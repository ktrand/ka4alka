<?php

namespace App\Service;

use App\Interface\SenderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender implements SenderInterface
{
    public function __construct(private readonly MailerInterface $mailer) {}

    /**
     * @throws TransportExceptionInterface
     */
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
