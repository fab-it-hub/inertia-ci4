<?php

namespace Inertia;

use Closure;
use CodeIgniter\HTTP\{RedirectResponse, RequestInterface};
use Inertia\Extras\{Arr, Http};

class ResponseFactory
{
    /** @var array */
    protected $sharedProps = [];

    /** @var \Closure|string|null */
    protected $version;

    public function share(string|array $key, $value = null): void
    {
        if (is_array($key)) {
            $this->sharedProps = array_merge($this->sharedProps, $key);
        } else {
            Arr::set($this->sharedProps, $key, $value);
        }
    }

    public function getShared(?string $key, $default = null)
    {
        if ($key) {
            return Arr::get($this->sharedProps, $key, $default);
        }

        return $this->sharedProps;
    }

    public function flushShared(): void
    {
        $this->sharedProps = [];
    }

    /**
     * @param \Closure|string|null $version
     * @return void
     */
    public function version($version): void
    {
        $this->version = $version;
    }

    public function getVersion(): string
    {
        return (string) Arr::value($this->version);
    }

    public function render(string $component, array $props = []): string
    {
        /** @var \Inertia\Config\Inertia */
        $config = \config('Inertia');

        return (string) new Response($component, array_merge($this->sharedProps, $props), $config->rootView, $this->getVersion());
    }

    public function location(RequestInterface|string $url): RedirectResponse
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

    public static function init($page, bool $isHead = false): string
    {
        if ($isHead) {
            return Directive::compileHead($page);
        }

        return Directive::compile($page);
    }
}