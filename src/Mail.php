<?php

namespace Osd\L4lHelpers;

use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;

class Mail
{
    /**
     * @return void
     */
    public static function checkSMTP(): void
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

            throw new RuntimeException($e->getMessage());
        }
    }
}
