<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OfficialLhkpn extends Model
{
    protected $fillable = [
        'official_id',
        'year',
        'position_at_year',
        'file_path',
        'external_url',
        'download_count',
    ];

    protected $casts = [
        'year' => 'integer',
        'download_count' => 'integer',
    ];

    public function official(): BelongsTo
    {
        return $this->belongsTo(Official::class);
    }

    public function getDocumentUrlAttribute(): string
    {
        if ($this->file_path) {
            return route('ppid.lhkpn.download', $this->id);
        }

        return $this->external_url ?? '#';
    }
}