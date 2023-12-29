<?php

namespace Inertia;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\{RedirectResponse, RequestInterface, ResponseInterface};

class Middleware implements FilterInterface
{
    public function withVersion()
    {
        if (file_exists($manifest = './build/manifest.json')) {
            return md5_file($manifest);
        }

        return null;
    }

    public function withShare(RequestInterface $request): array
    {
        return [
            'alert'  => fn () => session()->getFlashdata('alert'),
            'errors' => fn () => $this->resolveValidationErrors($request),
            'flash'  => fn () => ['success' => session()->getFlashdata('success'), 'error' => session()->getFlashdata('error')],
        ];
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        Inertia::version(fn () => $this->withVersion());
        Inertia::share($this->withShare($request));
    }

    /**
     * Handle the incoming request.
     *
     * @param  \CodeIgniter\HTTP\RequestInterface  $request
     * @param  \CodeIgniter\HTTP\ResponseInterface  $response
     * @param null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Vary', 'X-Inertia');

        if (!$request->header('X-Inertia')) {
            return $response;
        }

        if (request()->isCLI()) {
            return $response;
        }

        if (request()->is('get') && $request->hasHeader('X-Inertia-Version') && $request->header('X-Inertia-Version')->getValue() !== Inertia::getVersion()) {
            $response = $this->onVersionChange($request);
        }

        if ($response->getStatusCode() === $response::HTTP_OK && empty($response->getJSON())) {
            $response = $this->onEmptyResponse();
        }

        if ($response->getStatusCode() === $response::HTTP_FOUND && \in_array($request->getMethod(), ['put', 'patch', 'delete'])) {
            $response->setStatusCode($response::HTTP_SEE_OTHER);
        }

        return $response;
    }

    private function onEmptyResponse(): RedirectResponse
    {
        return \redirect()->back();
    }

    private function onVersionChange(RequestInterface $request): RedirectResponse
    {
        \session()->regenerate(true);

        return Inertia::location($request->getUri());
    }


    /**
     * Resolves and prepares validation errors in such
     * a way that they are easier to use client-side.
     *
     * @param  \CodeIgniter\HTTP\RequestInterface  $request
     * @return object
     */
    private function resolveValidationErrors(RequestInterface $request): object
    {
        service('session');

        /** @var \CodeIgniter\Validation\ValidationInterface */
        $validation = service('validation');

        $errors = session()->getFlashdata('errors') ?? $validation->getErrors();

        if (!$errors) {
            return (object) [];
        }

        if ($request->header('x-inertia-error-bag')) {
            return (object) [$request->header('x-inertia-error-bag') => $errors];
        }

        return (object) $errors;
    }
}
