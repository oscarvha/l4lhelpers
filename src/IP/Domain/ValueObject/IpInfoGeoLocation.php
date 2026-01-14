<?php

namespace Osd\L4lHelpers\IP\Domain\ValueObject;

final class IpInfoGeoLocation
{
    /**
     * @param string $city
     * @param string $country
     * @param string $timezone
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(
        private string $city,
        private string $country,
        private string $timezone,
        private float $latitude,
        private float $longitude
    ) {
        if ($latitude < -90 || $latitude > 90) {
            throw new \InvalidArgumentException('Invalid latitude');
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new \InvalidArgumentException('Invalid longitude');
        }
    }

    /**
     * @return string
     */
    public function city(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function country(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function timezone(): string
    {
        return $this->timezone;
    }

    /**
     * @return float
     */
    public function latitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function longitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param mixed $geoLocation
     * @return self
     */
    public static function fromArray(mixed $geoLocation):self
    {
        return new self(
            $geoLocation['city'],
            $geoLocation['country'],
            $geoLocation['timezone'],
            (float)$geoLocation['latitude'],
            (float)$geoLocation['longitude']
        );
    }
}
