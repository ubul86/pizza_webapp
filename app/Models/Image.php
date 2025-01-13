<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property \Illuminate\Database\Eloquent\Relations\Pivot $pivot
 */
class Image extends Model
{
    use HasFactory;

    const TYPE_STORAGE = 1;
    const TYPE_STORAGE_TEXT = 'storage';
    const TYPE_PUBLIC = 2;
    const TYPE_PUBLIC_TEXT = 'public';
    const TYPE_S3 = 3;
    const TYPE_S3_TEXT = 's3';

    const TYPE_UNKNOWN_TEXT = 'unknown';
    protected $fillable = ['path', 'type'];

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_STORAGE => self::TYPE_STORAGE_TEXT,
            self::TYPE_PUBLIC => self::TYPE_PUBLIC_TEXT,
            self::TYPE_S3 => self::TYPE_S3_TEXT,
            default => self::TYPE_UNKNOWN_TEXT,
        };
    }

    /**
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'products_images')->withTimestamps();
    }
}
