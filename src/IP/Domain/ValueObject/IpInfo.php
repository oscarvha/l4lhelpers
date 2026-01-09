<?php

namespace Osd\L4lHelpers\IP\Domain\ValueObject;


final class IpInfo
{
    private function __construct(private string $ipAddress,
                                 private IpInfoGeoLocation $geoLocation)
    {}

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data) : self
    {
        return new self(
            $data['ip'],
            IpInfoGeoLocation::fromArray($data['location'])
        );
    }

    public function geoLocation(): IpInfoGeoLocation
    {
        return $this->geoLocation;
    }
}
