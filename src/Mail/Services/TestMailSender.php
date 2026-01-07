<?php

namespace Osd\L4lHelpers\Mail\Services;

use Illuminate\Support\Facades\Mail;
use Osd\L4lHelpers\Mail\TestMail;
use RuntimeException;

final class TestMailSender
{
    public function send(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Invalid email address');
        }

        Mail::to($email)->send(new TestMail());
    }
}
