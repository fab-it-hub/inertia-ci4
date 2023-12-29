<?php

/**
 * This file is part of Inertia.js Codeigniter 4.
 *
 * (c) 2023 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Inertia\Ssr;

use Exception;
use Inertia\Config\Services;
use Inertia\Extras\Gateway;

class HttpGateway implements Gateway
{
    public function dispatch(array $page): ?Response
    {
        /** @var \Inertia\Config\Inertia */
        $config = \config('Inertia');

        if (! $config->isSsrEnabled) {
            return null;
        }

        $url = str_replace('/render', '', $config->ssrUrl) . '/render';

        try {
            $client  = Services::curlRequest();
            $apiCall = $client->setJSON($page)->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
            ]);
            $response = \json_decode($apiCall->getBody(), true);
        } catch (Exception $e) {
            return null;
        }

        if (null === $response) {
            return null;
        }

        return new Response(
            \implode("\n", $response['head']),
            $response['body']
        );
    }
}
