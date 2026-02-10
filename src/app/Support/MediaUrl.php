<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUrl
{
    public static function from(?string $path): ?string
    {
        $value = trim((string) $path);
        if ($value === '') return null;

        // external URL
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        // public paths
        if (Str::startsWith($value, ['/images/', '/storage/'])) {
            return $value;
        }

        if (Str::startsWith($value, ['images/', 'storage/'])) {
            return asset($value);
        }

        if (Str::startsWith($value, '/')) {
            return asset(ltrim($value, '/'));
        }

        // storage relative
        $normalized = preg_replace('#^/?storage/#', '', ltrim($value, '/'));
        return Storage::url($normalized);
    }
}
