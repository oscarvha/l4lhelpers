<?php

namespace Osd\L4lHelpers\IP\Application;

use Osd\L4lHelpers\IP\Domain\Contracts\IpProvider;
use Osd\L4lHelpers\IP\Domain\Contracts\UuidGenerator;
use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupConfigRepository;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupRepository;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpInfo;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpLookupId;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwner;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwnerRange;

final class GetIpLookup
{
    public function __construct(
        private IpProvider         $provider,
        private UuidGenerator      $idGenerator,
        private IpLookupRepository $ipLookupRepository,
        private IpLookupConfigRepository $configRepository
    ) {}

    /**
     * @param string $ip
     * @return IpLookup
     */
    public function execute(string $ip): IpLookup
    {
        $data = $this->provider->fetch($ip);

        $info = IpInfo::fromArray($data);
        $ownerData = $data['network']['autonomous_system'];
        $network = $data['network'];
        $range = new IpNetworkOwnerRange($network['cidr'], $network['hosts']['start'], $network['hosts']['end']);
        $owner = new IpNetworkOwner(
            $ownerData['asn'],
            $ownerData['name'],
            $ownerData['organization'],
            $ownerData['country'],
            $ownerData['rir'],
            $range
        );

        $lookup = new IpLookup(
            IpLookupId::fromString(
                $this->idGenerator->generate()
            ),
            $ip,
            $info,
            $owner,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
        );

        if ($this->configRepository->shouldPersist()) {

            if ($this->configRepository->mode() === 'override') {
                $this->ipLookupRepository->createOrUpdate($lookup);
            }else {
                $this->ipLookupRepository->create($lookup);
            }

        }

        return $lookup;
    }
}
