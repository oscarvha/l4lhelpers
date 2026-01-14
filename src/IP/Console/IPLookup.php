<?php

namespace Osd\L4lHelpers\IP\Console;

use Illuminate\Console\Command;
use Osd\L4lHelpers\IP\Application\GetIpLookup;

class IPLookup extends Command
{

    protected $signature = 'l4lhelpers:ip:lookup {ip?}';

    protected $description = 'Return IP info';


    public function handle(): int
    {

        $ip = $this->argument('ip');

        if (!$ip) {
            $this->error('IP is required');
            return self::FAILURE;
        }

        $service = app(GetIpLookup::class);

        $ipLookUp = $service->execute($ip);

        $this->info("IP: " . $ipLookUp->ip());

        return self::SUCCESS;
    }

}
