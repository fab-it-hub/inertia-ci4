<?php

namespace Inertia\Extras;

class Http
{
    public static function isInertiaRequest(): bool
    {
        return request()->hasHeader('X-Inertia');
    }

    public static function getHeaderValue(string $header, string $default = ''): string
    {
        if (request()->hasHeader($header)) {
            return request()->header($header)->getValue();
        }

        return $default;
    }
}
