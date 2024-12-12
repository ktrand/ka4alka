<?php

namespace App\Service;

use App\Interface\SenderInterface;

class SMSSender implements SenderInterface
{

    public function send(string $recipient, string $subject, string $body)
    {
        // TODO: Implement send() method.
    }
}