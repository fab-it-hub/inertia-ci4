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

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\View\View;
use Config\View as ConfigView;
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

    /**
     * @param array<string, mixed> $props
     */
    public function __construct(string $component, array $props, string $version = '')
    {
        $this->withComponent($component)->with($props)->withVersion($version);
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

    public function withComponent(string $component): static
    {
        $this->component = $component;

        return $this;
    }

    public function withVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function toResponse(?RequestInterface $request = null): View|ResponseInterface
    {
        $request ??= request();

        $only = array_filter(explode(',', Http::getHeaderValue('X-Inertia-Partial-Data', '', $request)));

        $props = ($only && Http::getHeaderValue('X-Inertia-Partial-Component', '', $request) === $this->component)
            ? Arr::only($this->props, $only)
            : $this->props;

        array_walk_recursive($props, static function (&$prop) {
            $prop = Arr::value($prop);
        });

        /** @var array{component: string, version: string, url: string, props: array<string, mixed>} */
        $page = [
            'component' => $this->component,
            'props'     => $props,
            'url'       => $request->getUri()->getPath(),
            'version'   => $this->version,
        ];

        if (Http::isInertiaRequest($request)) {
            return \response()->setJSON($page, true)->setHeader('Vary', 'X-Inertia')->setHeader('X-Inertia', 'true');
        }

        $view = new View(new ConfigView());
        $view->setData($this->viewData + ['page' => $page], 'raw');

        return $view;
    }
}
