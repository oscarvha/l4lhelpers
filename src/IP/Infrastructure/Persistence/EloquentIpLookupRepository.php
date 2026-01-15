<?php

namespace Osd\L4lHelpers\IP\Infrastructure\Persistence;

use Illuminate\Support\Facades\Schema;
use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupRepository;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpInfo;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpLookupId;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwner;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwnerRange;
use Osd\L4lHelpers\IP\Infrastructure\Exceptions\MissingIpLookupTableException;
use Osd\L4lHelpers\IP\Infrastructure\Mappers\IpSpamAssessmentMapper;
use Osd\L4lHelpers\IP\Infrastructure\Persistence\Models\IpLookupModel;
use Osd\L4lHelpers\IP\Infrastructure\Persistence\Models\IpSpamAssessmentModel;

/**
 *
 */
final class EloquentIpLookupRepository implements IpLookupRepository
{
    /**
     * @param IpLookup $ipLookup
     * @return void
     */
    public function create(IpLookup $ipLookup,
                           ?string $requestedByIp = null
    ): void
    {
        $this->checkTableExists();

        $model = IpLookupModel::create($this->mapToPersistence($ipLookup, $requestedByIp));

        if ($ipLookup->getSpamAssessment() !== null) {
            $this->persistSpamAssessment($model, $ipLookup);
        }
    }

    /**
     * @param IpLookup $ipLookup
     * @param string|null $requestedByIp
     * @return void
     */
    public function createOrUpdate(IpLookup $ipLookup,
                                   ?string $requestedByIp = null
    ): void
    {
        $this->checkTableExists();

        $data = $this->mapToPersistence($ipLookup, $requestedByIp);

        $model = IpLookupModel::updateOrCreate(
            [
                'ip' => $ipLookup->ip(),
                'id' => $ipLookup->uuid()->toString()
            ],
            $data
        );

        if ($ipLookup->getSpamAssessment() !== null) {
            $this->persistSpamAssessment($model, $ipLookup);
        }
    }

    /**
     * @param string $ip
     * @return IpLookup|null
     * @throws \Exception
     */
    public function findByIpAddress(string $ip): ?IpLookup
    {
        $this->checkTableExists();

        $record = IpLookupModel::with('spamAssessment')
            ->where('ip', $ip)
            ->first();

        return $record ? $this->mapToDomain($record) : null;
    }

    /**
     * @return void
     */
    private function checkTableExists(): void
    {
        if (!Schema::hasTable('ip_lookups')) {
            throw new MissingIpLookupTableException(
                'IpLookup persistence is enabled but the ip_lookups table does not exist.'
            );
        }
    }

    /**
     * @param string $ip
     * @param int $minutes
     * @return int
     */
    public function countRequestByIp(string $ip, int $minutes) :int
    {
        $from = now()->subMinutes($minutes);

        return IpLookupModel::where('requested_by_ip', $ip)
            ->where('created_at', '>=', $from)
            ->count();
    }

    /**
     * @param IpLookup $ipLookup
     * @param string|null $requestedByIp
     * @return array
     */
    private function mapToPersistence(IpLookup $ipLookup, string $requestedByIp = null): array
    {
        return [
            'id' => $ipLookup->uuid()->toString(),
            'ip' => $ipLookup->ip(),

            'geo_city' => $ipLookup->info()->geoLocation()->city(),
            'geo_country' => $ipLookup->info()->geoLocation()->country(),
            'geo_timezone' => $ipLookup->info()->geoLocation()->timeZone(),
            'geo_latitude' => $ipLookup->info()->geoLocation()->latitude(),
            'geo_longitude' => $ipLookup->info()->geoLocation()->longitude(),

            'asn' => $ipLookup->owner()->asn(),
            'owner_name' => $ipLookup->owner()->name(),
            'owner_organization' => $ipLookup->owner()->organization(),
            'owner_country' => $ipLookup->owner()->country(),
            'rir' => $ipLookup->owner()->rir(),
            'cidr' => $ipLookup->owner()->range()->cidr(),
            'network_start_ip' => $ipLookup->owner()->range()->startIp(),
            'network_end_ip' => $ipLookup->owner()->range()->endIp(),

            'created_at' => $ipLookup->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $ipLookup->updatedAt()->format('Y-m-d H:i:s'),
            'requested_by_ip' => $requestedByIp,
        ];
    }

    /**
     * @param IpLookupModel $record
     * @return IpLookup
     * @throws \Exception
     */
    private function mapToDomain(IpLookupModel $record): IpLookup
    {
        $info = IpInfo::fromArray([
            'ip' => $record->ip,
            'location' => [
                'city' => $record->geo_city,
                'country' => $record->geo_country,
                'timezone' => $record->geo_timezone,
                'latitude' => $record->geo_latitude,
                'longitude' => $record->geo_longitude,
            ],
        ]);

        $range = new IpNetworkOwnerRange(
            $record->cidr,
            $record->network_start_ip,
            $record->network_end_ip
        );

        $owner = new IpNetworkOwner(
            $record->asn,
            $record->owner_name,
            $record->owner_organization,
            $record->owner_country,
            $record->rir,
            $range
        );


        return IpLookup::recreate(
            IpLookupId::fromString($record->id),
            $record->ip,
            $info,
            $owner,
            new \DateTimeImmutable($record->created_at),
            $record->spamAssessment()->first()
                ? IpSpamAssessmentMapper::fromPersistence($record->spamAssessment()->first())
                : null
        );
    }

    /**
     * @param IpLookupModel $lookupModel
     * @param IpLookup $ipLookup
     * @return void
     */
    private function persistSpamAssessment(
        IpLookupModel $lookupModel,
        IpLookup $ipLookup,
    ): void {
        $assessment = $ipLookup->getSpamAssessment();

        if (!$assessment) {
            return;
        }

        $data = [
            'id' => uniqid('', true),
            'ip_lookup_id' => $lookupModel->id,
            'spam_score' => $assessment->spamScore(),
            'confidence' => $assessment->confidence(),
            'type' => $assessment->type(),
            'explanation' => $assessment->explanation(),
            'explanation_es' => $assessment->explanationEs(),
            'provider' => $assessment->provider(),
            'model' => $assessment->model(),
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'requested_by_ip' => null,
        ];

        IpSpamAssessmentModel::updateOrCreate(
            ['ip_lookup_id' => $lookupModel->id],
            $data
        );
    }
}
