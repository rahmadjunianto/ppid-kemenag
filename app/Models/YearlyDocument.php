<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class YearlyDocument extends Model
{
    protected $fillable = [
        'type',
        'title',
        'year',
        'description',
        'file_path',
        'external_url',
        'download_count',
        'sort_order',
    ];

    protected $casts = [
        'year' => 'integer',
        'download_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getDocumentUrlAttribute(): string
    {
        if ($this->file_path) {
            return route('ppid.yearly.download', $this->id);
        }

        return $this->external_url ?? '#';
    }
}