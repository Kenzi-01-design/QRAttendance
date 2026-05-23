<?php

namespace App\Services;

class StudentNameNormalizer
{
    public static function normalize(string $value): string
    {
        $value = preg_replace('/\s+/', ' ', trim($value)) ?? '';

        return mb_strtolower($value);
    }
}
