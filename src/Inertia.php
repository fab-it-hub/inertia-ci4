<?php

namespace Inertia;

use Inertia\Config\Services;

/**
 * Inertia.
 *
 * @method static void share(string|array $key, $value = null)
 * @method static mixed getShared(?string $key, $default = null)
 * @method static void flushShared()
 * @method static void version(\Closure|string|null $version)
 * @method static string getVersion()
 * @method static \Inertia\Response render(string $component, array $props = [], array $viewData = [])
 * @method static \CodeIgniter\HTTP\RedirectResponse location(\CodeIgniter\HTTP\Request|string $url):
 * @method static string init($page, bool $isHead)
 *
 * @see \Inertia\ResponseFactory
 */
class Inertia
{
    public static function __callStatic($method, $arguments)
    {
        return Services::inertia()->{$method}(...$arguments);
    }
}
