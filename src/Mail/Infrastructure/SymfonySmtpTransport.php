<?php

namespace Osd\L4lHelpers\Mail\Infrastructure;

use Osd\L4lHelpers\Mail\Contracts\SmtpTransport;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;

final class SymfonySmtpTransport implements SmtpTransport
{
    public function start(): void
    {
        try {
            $dsn = config('mail.mailers.smtp');

            $transport = Transport::fromDsn(sprintf(
                'smtp://%s:%s@%s:%s?encryption=%s',
                urlencode($dsn['username']),
                urlencode($dsn['password']),
                $dsn['host'],
                $dsn['port'],
                $dsn['encryption'] ?? 'tls'
            ));

            $transport->start();

        } catch (TransportExceptionInterface $e) {
            throw new RuntimeException('SMTP connection failed: ' . $e->getMessage());
        }
    }
}
