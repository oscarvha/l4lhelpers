<?php

namespace Osd\L4lHelpers\Mail\Contracts;

interface SmtpTransport
{
    public function start(): void;
}
