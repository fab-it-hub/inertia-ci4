<?php

/**
 * This file is part of Inertia.js Codeigniter 4.
 *
 * (c) 2023 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Inertia\Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function inertia(bool $getShared = true): ?\Inertia\ResponseFactory
    {
        if ($getShared) {
            return static::getSharedInstance('inertia');
        }

        return new \Inertia\ResponseFactory();
    }

    public static function httpGateway($getShared = true): ?\Inertia\Ssr\HttpGateway
    {
        if ($getShared) {
            return static::getSharedInstance('httpGateway');
        }

        return new \Inertia\Ssr\HttpGateway();
    }
}
