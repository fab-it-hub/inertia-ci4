<?php

/**
 * This file is part of Inertia.js Codeigniter 4.
 *
 * (c) 2023 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Inertia\Extras;

class Http
{
    public static function isInertiaRequest(): bool
    {
        return request()->hasHeader('X-Inertia');
    }

    /**
     * @return (string|string[])[]|string
     * @psalm-return array<int|string, array<string, string>|string>|string
     */
    public static function getHeaderValue(string $header, string $default = ''): array|string
    {
        if (request()->hasHeader($header)) {
            return request()->header($header)->getValue();
        }

        return $default;
    }
}
