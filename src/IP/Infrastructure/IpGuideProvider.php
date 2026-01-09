<?php

namespace Osd\L4lHelpers\IP\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Osd\L4lHelpers\IP\Domain\Contracts\IpProvider;
use RuntimeException;

class IpGuideProvider implements IpProvider
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://ip.guide/',
            'timeout' => 10,
            'http_errors' => false,
        ]);
    }

    /**
     * @throws \JsonException|GuzzleException
     */
    public function fetch(string $ip): array
    {
        $response = $this->client->get($ip, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('IP provider error');
        }

        $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new RuntimeException('Invalid response');
        }

        return [
            'ip' => $data['ip'] ?? null,
            'network' => [
                'cidr' => $data['network']['cidr'] ?? null,
                'hosts' => [
                    'start' => $data['network']['hosts']['start'] ?? null,
                    'end' => $data['network']['hosts']['end'] ?? null,
                ],
                'autonomous_system' => [
                    'asn' => $data['network']['autonomous_system']['asn'] ?? null,
                    'name' => $data['network']['autonomous_system']['name'] ?? null,
                    'organization' => $data['network']['autonomous_system']['organization'] ?? null,
                    'country' => $data['network']['autonomous_system']['country'] ?? null,
                    'rir' => $data['network']['autonomous_system']['rir'] ?? null,
                ],
            ],
            'location' => [
                'city' => $data['location']['city'] ?? null,
                'country' => $data['location']['country'] ?? null,
                'timezone' => $data['location']['timezone'] ?? null,
                'latitude' => $data['location']['latitude'] ?? null,
                'longitude' => $data['location']['longitude'] ?? null,
            ],
        ];
    }
}
