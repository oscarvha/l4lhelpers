<?php

namespace Osd\L4lHelpers\IP\Domain\ValueObject;


final class IpInfo
{
    public function __construct(private IpInfoGeoLocation $geoLocation)
    {}

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data) : self
    {
        return new self(
            IpInfoGeoLocation::fromArray($data['location'])
        );
    }

    public function geoLocation(): IpInfoGeoLocation
    {
        return $this->geoLocation;
    }
}
