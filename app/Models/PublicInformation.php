<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str; 

class PublicInformation extends Model
{
    protected $table = 'public_informations';

    protected $fillable = [
        'classification',
        'parent_id',
        'code',
        'title',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    // Otomatis buat slug jika kosong saat disimpan
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
    // Relasi ke Kategori Induk (A, B, C)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Relasi ke Sub-Menu / Anak (1, 2, 3)
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    // Relasi ke Dokumen / Link Multi-Tahun
    public function items(): HasMany
    {
        return $this->hasMany(PublicInformationItem::class, 'public_information_id')
            ->orderByDesc('year')
            ->orderBy('sort_order');
    }

    // Helper label untuk dropdown Kategori Induk di Admin
    public function getFullTitleAttribute(): string
    {
        return ($this->code ? "{$this->code}. " : '') . $this->title;
    }

    // Otomatis hapus Cache saat data disimpan atau dihapus di Admin
    protected static function booted(): void
    {
        static::saved(fn(self $model) => Cache::forget("ppid_menu_{$model->classification}"));
        static::deleted(fn(self $model) => Cache::forget("ppid_menu_{$model->classification}"));
    }
}
