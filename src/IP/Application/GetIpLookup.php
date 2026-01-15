<?php

namespace Osd\L4lHelpers\IP\Application;

use Osd\L4lHelpers\IP\Application\Exceptions\IpRequestLimitExceededException;
use Osd\L4lHelpers\IP\Domain\Contracts\IpProvider;
use Osd\L4lHelpers\IP\Domain\Contracts\IpSpamService;
use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupConfigRepository;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupRepository;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpSpamAssessment;
use Osd\L4lHelpers\IP\IP;

final class GetIpLookup
{
    public function __construct(
        private readonly IpProvider $provider,
        private readonly IpLookupRepository $ipLookupRepository,
        private readonly IpLookupConfigRepository $configRepository,
        private readonly IpSpamService $spamService

    ) {}

    /**
     * @param string $ip
     * @return IpLookup
     */
    public function execute(string $ip): IpLookup
    {
        $now = new \DateTimeImmutable();
        $ttl = $this->configRepository->refreshTtl();

        if ($ttl !== null) {
            $existing = $this->ipLookupRepository->findByIpAddress($ip);

            if ($existing !== null && !$existing->isExpired($ttl, $now)) {
                    return $existing;
            }
        }

        if ($this->configRepository->limitByIp() && $this->configRepository->limitByIp()) {
            $count = $this->ipLookupRepository->countRequestByIp(Ip::real(), $this->configRepository->limitByIpDurationMinutes());
            if ($count >= $this->configRepository->limitByIp()) {
                throw new IpRequestLimitExceededException(
                    $ip,
                    $this->configRepository->limitByIp(),
                    $this->configRepository->limitByIpDurationMinutes()
                );
            }
        }

        $ipLookUp = $this->provider->fetch($ip);

        if ($this->configRepository->spamAnalysisEnabled() && empty(!$this->configRepository->spamAnalysisApiKey())) {

            $spamAssessment = $this->spamService->analyze($ipLookUp);

            $ipLookUp->setSpamAssessment($spamAssessment);
        }


        if ($this->configRepository->shouldPersist()) {

            if ($this->configRepository->mode() === 'override') {
                $this->ipLookupRepository->createOrUpdate($ipLookUp,Ip::real());
            }else {
                $this->ipLookupRepository->create($ipLookUp,Ip::real());
            }

        }

        return $ipLookUp;
    }

}
