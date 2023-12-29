<?php

namespace Inertia\Extras;

use Inertia\Ssr\Response;

interface Gateway
{
    public function dispatch(array $page): ?Response;
}
