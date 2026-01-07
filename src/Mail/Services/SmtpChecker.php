<?php

namespace Osd\L4lHelpers\Mail\Services;

use Osd\L4lHelpers\Mail\Contracts\SmtpTransport;
use RuntimeException;

final readonly class SmtpChecker
{
    public function __construct(
        private SmtpTransport $transport,
        private string $defaultMailer
    ) {}

    public function check(): void
    {
        if ($this->defaultMailer !== 'smtp') {
            throw new RuntimeException('SMTP is not the default mail driver');
        }

        $this->transport->start();
    }
}
