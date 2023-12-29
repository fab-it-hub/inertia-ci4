<?php

namespace Inertia;

use Inertia\Config\Services;
use Inertia\Ssr\Response;

class Directive
{
    protected static ?Response $__inertiaSsr;

    public static function compile(array $page, string $expression = ''): string
    {
        $id = trim(trim($expression), "\'\"") ?: 'app';
        $inertiaSsr = self::withSsr($page);

        $template = '<div id="' . $id . '" data-page="' . htmlentities(json_encode($page)) . '"></div>';

        if ($inertiaSsr instanceof Response) {
            $template = $inertiaSsr->body;
        }

        return implode(' ', array_map('trim', explode("\n", $template)));
    }

    public static function compileHead(array $page): string
    {
        $template = '';
        $inertiaSsr = self::withSsr($page);

        if ($inertiaSsr instanceof Response) {
            $template = $inertiaSsr->head;
        }

        return implode(' ', array_map('trim', explode("\n", $template)));
    }

    protected static function withSsr(array $page): Response|null
    {
        if (!isset(self::$__inertiaSsr) && empty(self::$__inertiaSsr)) {
            $__inertiaSsr = Services::httpGateway()->dispatch($page);

            self::$__inertiaSsr = $__inertiaSsr;
        }

        return self::$__inertiaSsr;
    }
}
