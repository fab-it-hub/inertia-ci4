<?php

/**
 * This file is part of Inertia.js Codeigniter 4.
 *
 * (c) 2023 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Inertia;

use Inertia\Config\Services;

class Directive
{
    protected static ?\Inertia\Ssr\Response $__inertiaSsr;

    /**
     * @param array{component: string, version: string, url: string, props: array<string, mixed>} $page
     */
    public static function compile(array $page, string $expression = ''): string
    {
        $id         = trim(trim($expression), "\\'\"") ?: 'app';
        $inertiaSsr = static::withSsr($page);

        $template = '<div id="' . $id . '" data-page="' . htmlentities(json_encode($page)) . '"></div>';

        if ($inertiaSsr instanceof \Inertia\Ssr\Response) {
            $template = $inertiaSsr->body;
        }

        return implode(' ', array_map('trim', explode("\n", $template)));
    }

    /**
     * @param array{component: string, version: string, url: string, props: array<string, mixed>} $page
     */
    public static function compileHead(array $page): string
    {
        $template   = '';
        $inertiaSsr = static::withSsr($page);

        if ($inertiaSsr instanceof \Inertia\Ssr\Response) {
            $template = $inertiaSsr->head;
        }

        return implode(' ', array_map('trim', explode("\n", $template)));
    }

    /**
     * @param array{component: string, version: string, url: string, props: array<string, mixed>} $page
     */
    protected static function withSsr(array $page): ?Ssr\Response
    {
        if (! isset(static::$__inertiaSsr) && empty(static::$__inertiaSsr)) {
            $__inertiaSsr = Services::httpGateway()->dispatch($page);

            static::$__inertiaSsr = $__inertiaSsr;
        }

        return static::$__inertiaSsr;
    }
}
