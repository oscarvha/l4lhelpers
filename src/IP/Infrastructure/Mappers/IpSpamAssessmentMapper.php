<?php

namespace Osd\L4lHelpers\IP\Infrastructure\Mappers;

use Osd\L4lHelpers\IP\Domain\ValueObject\IpSpamAssessment;
use Osd\L4lHelpers\IP\Infrastructure\Persistence\Models\IpSpamAssessmentModel;

final class IpSpamAssessmentMapper
{
    /**
     * @param IpSpamAssessmentModel $external
     * @return IpSpamAssessment
     */
    public static function fromPersistence(IpSpamAssessmentModel $external): IpSpamAssessment
    {
        return new IpSpamAssessment(
            $external->spam_score,
            $external->confidence,
            $external->type,
            $external->explanation,
            $external->explanation_es,
            $external->model,
            $external->provider

        );
    }
}
