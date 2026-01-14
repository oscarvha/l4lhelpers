<?php

namespace Osd\L4lHelpers\IP\Domain\Contracts;

use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpSpamAssessment;

interface IpSpamService
{
    public function analyze(IpLookup $ipLookup): IpSpamAssessment;
}
