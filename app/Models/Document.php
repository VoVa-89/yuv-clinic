<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'file_path', 'mime', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Корневой относительный URL файла (текущий хост/порт из браузера, без привязки к APP_URL).
     */
    public function publicStorageHref(): string
    {
        $path = str_replace('\\', '/', $this->file_path);

        return '/storage/'.$path;
    }

    /**
     * Краткая метка типа файла по MIME (для публичной страницы документов).
     */
    public function kindLabelFromMime(): string
    {
        $m = strtolower(trim((string) $this->mime));

        if ($m === '') {
            return '';
        }

        return match (true) {
            str_contains($m, 'pdf') => 'PDF',
            str_starts_with($m, 'image/jpeg')
                || str_starts_with($m, 'image/jpg') => 'JPEG',
            str_starts_with($m, 'image/png') => 'PNG',
            str_starts_with($m, 'image/webp') => 'WEBP',
            default => '',
        };
    }
}
