<?php

declare(strict_types=1);

namespace ShortUrl\Support;

class CodeGenerator
{
    public static function generate(int $length = 6): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $maxIndex = strlen($alphabet) - 1;
        $code = '';

        for ($index = 0; $index < $length; $index++) {
            $code .= $alphabet[random_int(0, $maxIndex)];
        }

        return $code;
    }
}
