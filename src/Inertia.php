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
 * @method static string init(array{component: string, version: string, url: string, props: array<string, mixed>} $page, bool $isHead)
 * @method static \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\ResponseInterface location(\CodeIgniter\HTTP\Request|string $url):
 * @method static \Inertia\Response render(string $component, array $props = [], array $viewData = [])
 * @method static void              share(string|array $key, $value = null)
 * @method static void              version(\Closure|string|null $version)
 *
 * @see ResponseFactory
 */
class Inertia
{
    /**
     * @param array<int|string, mixed> $arguments
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $arguments)
    {
        return Services::inertia()->{$method}(...$arguments);
    }
}
