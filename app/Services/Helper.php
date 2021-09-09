<?php

declare(strict_types=1);

namespace App\Services;

class Helper
{
    public static function clear(?string $str): string
    {
        if (!$str) {
            return '';
        }

        $str = str_replace(',', '.', $str);
        $str = preg_replace('#[^0-9\.]+#', '', $str);
        return trim($str, '.');
    }
}
