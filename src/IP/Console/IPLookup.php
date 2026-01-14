<?php

namespace Osd\L4lHelpers\IP\Console;

use Illuminate\Console\Command;
use Osd\IpCheckSpam\Application\Config\IpSpamConfig;
use Osd\IpCheckSpam\Domain\DTO\IpSpamInput;
use Osd\L4lHelpers\IP\Application\GetIpLookup;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpSpamAssessment;

class IPLookup extends Command
{

    protected $signature = 'l4lhelpers:ip:lookup {ip?}';

    protected $description = 'Return IP info';


    /**
     * @return int
     */
    public function handle(): int
    {
        $ip = $this->argument('ip');

        $service = app(GetIpLookup::class);

        $ipLookUp = $service->execute($ip);

        return Command::SUCCESS;
    }

}
