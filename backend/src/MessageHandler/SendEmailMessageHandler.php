<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Service\EmailSender;

class SendEmailMessageHandler implements MessageHandlerInterface
{
    public function __construct(private EmailSender $emailSender) {}

    public function __invoke(SendEmailMessage $message): void
    {
        $this->emailSender->send(
            $message->getRecipient(),
            $message->getSubject(),
            $message->getBody()
        );
    }
}
