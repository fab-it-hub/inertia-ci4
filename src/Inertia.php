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

/**
 * Inertia.
 *
 * @method static void   flushShared()
 * @method static mixed  getShared(?string $key, $default = null)
 * @method static string getVersion()
 * @method static string init($page, bool $isHead)
 * @method static \CodeIgniter\HTTP\RedirectResponse location(\CodeIgniter\HTTP\Request|string $url):
 * @method static \Inertia\Response render(string $component, array $props = [], array $viewData = [])
 * @method static void              share(string|array $key, $value = null)
 * @method static void              version(\Closure|string|null $version)
 *
 * @see ResponseFactory
 */
class Inertia
{
    public static function __callStatic($method, $arguments)
    {
        return Services::inertia()->{$method}(...$arguments);
    }
}
