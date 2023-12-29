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
use Inertia\Extras\Arr;
use Inertia\Extras\Http;

class Response
{
    /**
     * @var array<string, mixed>
     */
    protected array $props      = [];

    /**
     * @var array<string, mixed>
     */
    protected array $viewData   = [];

    protected string $version   = '';
    protected string $component = '';
    protected string $rootView  = 'app';

    /**
     * @param array<string, mixed> $props
     */
    public function __construct(string $component, array $props, string $rootView = 'app', string $version = '')
    {
        $this->withComponent($component)->with($props)->withRootView($rootView)->withVersion($version);
    }

    /**
     * @param array<string, mixed>|string $key
     * @param mixed                       $value
     *
     * @return $this
     */
    public function with($key, $value = null): self
    {
        if (is_array($key)) {
            $this->props = array_merge($this->props, $key);
        } else {
            $this->props[$key] = $value;
        }

        return $this;
    }

    public function withComponent(string $component): self
    {
        $this->component = $component;

        return $this;
    }

    /**
     * @param array<string, mixed>|string $key
     * @param mixed                       $value
     *
     * @return $this
     */
    public function withViewData($key, $value = null): self
    {
        if (is_array($key)) {
            $this->viewData = array_merge($this->viewData, $key);
        } else {
            $this->viewData[$key] = $value;
        }

        return $this;
    }

    public function withVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function withRootView(string $rootView): self
    {
        $this->rootView = $rootView;

        return $this;
    }

    public function __toString()
    {
        $only = array_filter(explode(',', Http::getHeaderValue('X-Inertia-Partial-Data')));

        $props = ($only && Http::getHeaderValue('X-Inertia-Partial-Component') === $this->component)
            ? Arr::only($this->props, $only)
            : $this->props;

        array_walk_recursive($props, static function (&$prop) {
            $prop = Arr::value($prop);
        });

        /** @var array{component: string, version: string, url: string, props: array<string, mixed>} */
        $page = [
            'component' => $this->component,
            'props'     => $props,
            'url'       => \request()->getUri()->getPath(),
            'version'   => $this->version,
        ];

        if (Http::isInertiaRequest()) {
            return \response()
                ->setJSON($page, true)
                ->setHeader('Vary', 'X-Inertia')
                ->setHeader('X-Inertia', 'true')
                ->getJSON();
        }

        return Services::renderer()
            ->setData($this->viewData + ['page' => $page], 'raw')
            ->render($this->rootView);
    }
}
