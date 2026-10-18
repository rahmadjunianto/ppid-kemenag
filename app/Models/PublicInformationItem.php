<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PublicInformationItem extends Model
{
    protected $table = 'public_information_items';

    protected $fillable = [
        'public_information_id',
        'label',
        'year',
        'action_type',
        'file_path',
        'external_url',
        'internal_url',
        'content',
        'map_embed_url',
        'download_count',
        'sort_order',
    ];

    protected $casts = [
        'year' => 'integer',
        'download_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function information(): BelongsTo
    {
        return $this->belongsTo(PublicInformation::class, 'public_information_id');
    }

    // Helper untuk mengambil URL tujuan di Frontend Blade
    public function getTargetUrlAttribute(): string
    {
        return match ($this->action_type) {
            'file' => \Illuminate\Support\Facades\Route::has('ppid.item.download')
                ? route('ppid.item.download', $this->id)
                : ($this->file_path ? Storage::url($this->file_path) : '#'),
            'link' => $this->external_url ?? '#',
            'content' => \Illuminate\Support\Facades\Route::has('ppid.item.show')
                ? route('ppid.item.show', $this->id)
                : '#',
            default => '#',
        };
    }

    protected static function booted(): void
    {
        // Bersihkan cache halaman publik saat dokumen diupdate
        static::saved(function (self $item) {
            if ($item->information) {
                Cache::forget("ppid_menu_{$item->information->classification}");
            }
        });

        // Hapus juga file fisik PDF dari storage jika item dihapus dari admin
        static::deleted(function (self $item) {
            if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
                Storage::disk('public')->delete($item->file_path);
            }
            if ($item->information) {
                Cache::forget("ppid_menu_{$item->information->classification}");
            }
        });
    }
}