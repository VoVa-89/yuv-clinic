<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'procedure_name',
        'nomenclature_code',
        'price_display',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /** @return array<string, string> */
    public static function sectionTitles(): array
    {
        return config('price_sections', []);
    }

    /** @return list<string> */
    public static function allowedSectionKeys(): array
    {
        return array_keys(self::sectionTitles());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
