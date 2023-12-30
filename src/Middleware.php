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

use Closure;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\ValidationInterface;
use Inertia\Extras\Http;

/**
 * @psalm-api
 */
class Middleware implements FilterInterface
{
    public function withVersion(): string|false|null
    {
        if (file_exists($manifest = './build/manifest.json')) {
            return md5_file($manifest);
        }

        return null;
    }

    /**
     * @psalm-return array{alert: Closure():?string, errors: Closure():object, flash: Closure():array{success: ?string, error: ?string}}
     * @return array{alert: Closure():?string, errors: Closure():object, flash: Closure():array{success: ?string, error: ?string}}
     */
    public function withShare(RequestInterface $request): array
    {
        return [
            'alert'  => static fn () => session()->getFlashdata('alert'),
            'errors' => fn () => $this->resolveValidationErrors($request),
            'flash'  => static fn () => ['success' => session()->getFlashdata('success'), 'error' => session()->getFlashdata('error')],
        ];
    }

    /**
     * @param array<int|string, mixed> $arguments
     *
     * @return void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        Inertia::version(fn () => $this->withVersion());
        Inertia::share($this->withShare($request));
    }

    /**
     * Handle the incoming request.
     *
     * @param null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Vary', 'X-Inertia');

        if (!$request->hasHeader('X-Inertia')) {
            return $response;
        }

        if (request()->isCLI()) {
            return $response;
        }

        if (request()->is('get') && Http::getHeaderValue('X-Inertia-Version')  !== Inertia::getVersion()) {
            $response = $this->onVersionChange($request);
        }

        if ($response->getStatusCode() === $response::HTTP_OK && empty($response->getJSON())) {
            $response = $this->onEmptyResponse();
        }

        if (
            $response->getStatusCode() === $response::HTTP_FOUND
            && (request()->is('put') || request()->is('patch') || request()->is('delete'))
        ) {
            $response->setStatusCode($response::HTTP_SEE_OTHER);
        }

        return $response;
    }

    private function onEmptyResponse(): RedirectResponse
    {
        return \redirect()->back();
    }

    private function onVersionChange(RequestInterface $request): RedirectResponse|ResponseInterface
    {
        \session()->regenerate(true);

        return Inertia::location($request->getUri());
    }

    /**
     * Resolves and prepares validation errors in such
     * a way that they are easier to use client-side.
     */
    private function resolveValidationErrors(RequestInterface $request): object
    {
        service('session');

        /** @var ValidationInterface */
        $validation = service('validation');

        $errors = session()->getFlashdata('errors') ?? $validation->getErrors();

        if (!$errors) {
            return (object) [];
        }

        if ($request->hasHeader('x-inertia-error-bag')) {
            return (object) [Http::getHeaderValue('x-inertia-error-bag') => $errors];
        }

        return (object) $errors;
    }
}
