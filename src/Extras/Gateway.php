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

use Inertia\Ssr\Response;

interface Gateway
{
    /**
     * @param array{component: string, version: string, url: string, props: array<string, mixed>} $page
     */
    public function dispatch(array $page): ?Response;
}
