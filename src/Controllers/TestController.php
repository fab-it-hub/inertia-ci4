<?php

/**
 * This file is part of Inertia.js Codeigniter 4.
 *
 * (c) 2023 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Inertia\Controllers;

use App\Controllers\BaseController;

/** @psalm-api */
class TestController extends BaseController
{
    /**
     * @psalm-api
     */
    public function index(): string
    {
        return 'ok';
    }
}
