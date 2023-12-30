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
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Inertia\Extras\Arr;
use Inertia\Extras\Http;

/**
 * @psalm-api
 */
class ResponseFactory
{
    /**
     * @var array<string, mixed>
     */
    protected $sharedProps = [];

    /**
     * @var Closure|string|null
     */
    protected $version;

    /**
     * @param array<string, mixed>|string $key
     * @param mixed                       $value
     *
     * @psalm-api
     */
    public function share(string|array $key, $value = null): void
    {
        if (is_array($key)) {
            $this->sharedProps = array_merge($this->sharedProps, $key);
        } else {
            Arr::set($this->sharedProps, $key, $value);
        }
    }

    /**
     * @param mixed $default
     *
     * @return array<string, mixed>
     *
     * @psalm-api
     */
    public function getShared(?string $key, $default = null)
    {
        if ($key) {
            return Arr::get($this->sharedProps, $key, $default);
        }

        return $this->sharedProps;
    }

    /**
     * @psalm-api
     */
    public function flushShared(): void
    {
        $this->sharedProps = [];
    }

    /**
     * @param Closure|string|null $version
     *
     * @psalm-api
     */
    public function version($version): void
    {
        $this->version = $version;
    }

    /**
     * @psalm-api
     */
    public function getVersion(): string
    {
        return (string) Arr::value($this->version);
    }

    /**
     * @psalm-api
     *
     * @param array<string, mixed> $props
     */
    public function render(string $component, array $props = []): string
    {
        /** @var Config\Inertia */
        $config = \config('Inertia');

        return (string) new Response($component, array_merge($this->sharedProps, $props), $config->rootView, $this->getVersion());
    }

    /**
     * @psalm-api
     */
    public function location(RequestInterface|string $url): ResponseInterface
    {
        if ($url instanceof RequestInterface) {
            $url = $url->getUri();
        }

        if (Http::isInertiaRequest()) {
            session()->set('_ci_previous_url', $url);

            return \response()->setStatusCode(\response()::HTTP_CONFLICT)->setHeader('X-Inertia-Location', $url);
        }

        return \redirect()->to($url, \response()::HTTP_SEE_OTHER);
    }

    /**
     * @param array{component: string, version: string, url: string, props: array<string, mixed>} $page
     *
     * @psalm-api
     */
    public static function init(array $page, bool $isHead = false): string
    {
        if ($isHead) {
            return Directive::compileHead($page);
        }

        return Directive::compile($page);
    }
}
