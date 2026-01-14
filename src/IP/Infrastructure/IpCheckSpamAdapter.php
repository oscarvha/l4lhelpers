<?php

namespace Osd\L4lHelpers\IP\Infrastructure;

use Osd\IpCheckSpam\Application\Config\IpSpamConfig;
use Osd\IpCheckSpam\Bootstrap\IpCheckSpamFactory;
use Osd\IpCheckSpam\Domain\DTO\IpSpamInput;
use Osd\L4lHelpers\IP\Domain\Contracts\IpSpamService;
use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupConfigRepository;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpSpamAssessment;

readonly class IpCheckSpamAdapter implements IpSpamService
{
    public function __construct(
        private IpLookupConfigRepository $configRepository
    ) {}

    /**
     * @param IpLookup $ipLookup
     * @return IpSpamAssessment
     */
    public function analyze(IpLookup $ipLookup): IpSpamAssessment
    {
        $input = new IpSpamInput(
            ip: $ipLookup->ip(),
            asn: $ipLookup->owner()->asn(),
            isp: $ipLookup->owner()->name(),
            organization: $ipLookup->owner()->organization(),
            country: $ipLookup->info()->geoLocation()->country(),
            city: $ipLookup->info()->geoLocation()->city(),
            cidr: $ipLookup->owner()->range()->cidr()
        );

        $apiKey = new IpSpamConfig($this->configRepository->spamAnalysisApiKey());

        $analyze = IpCheckSpamFactory::create($apiKey);
        $analysis = $analyze->execute($input);

        return new IpSpamAssessment(
            spamScore: $analysis->spamScore(),
            confidence: $analysis->confidence(),
            type: $analysis->type(),
            explanation: $analysis->explanation(),
            explanationEs: $analysis->explanationEs(),
            model: $analysis->model(),
            provider: $analysis->provider()
        );

    }
}
