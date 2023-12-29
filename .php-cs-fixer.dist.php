<?php

use CodeIgniter\CodingStandard\CodeIgniter4;
use Nexus\CsConfig\Factory;

return Factory::create(new CodeIgniter4, [
    'binary_operator_spaces' => false,
])->forLibrary(
    'Inertia.js Codeigniter 4',
    'Fab IT Hub',
    'hello@fabithub.com',
    2023
);
