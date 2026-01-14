<?php

namespace Osd\L4lHelpers\IP\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class IpSpamAssessmentModel extends Model
{
    protected $table = 'ip_spam_assessments';

    protected $fillable = [
        'id',
        'ip_lookup_id',
        'spam_score',
        'confidence',
        'type',
        'explanation',
        'explanation_es',
        'provider',
        'model',
        'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'spam_score' => 'float',
        'created_at' => 'datetime',
    ];

    public function ipLookup(): BelongsTo
    {
        return $this->belongsTo(IpLookupModel::class, 'ip_lookup_id');
    }
}
