<?php

namespace Inertia\Config;

use CodeIgniter\Config\BaseConfig;

class Inertia extends BaseConfig
{
    public string $rootView = 'app';
    public bool $isSsrEnabled = false;
    public string $ssrUrl = 'http://127.0.0.1:13714';
}
