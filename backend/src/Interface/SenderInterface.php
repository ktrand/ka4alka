<?php

namespace App\Interface;

interface SenderInterface
{
    public function send(string $recipient, string $subject, string $body);
}