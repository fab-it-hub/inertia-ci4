<?php

namespace Inertia\Ssr;

class Response
{
    public string $head;
    public string $body;

    public function __construct(string $head, string $body)
    {
        $this->head = $head;
        $this->body = $body;
    }
}
