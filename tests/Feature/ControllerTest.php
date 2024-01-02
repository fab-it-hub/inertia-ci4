<?php

declare(strict_types=1);

/**
 * This file is part of Inertia.js Codeigniter 4.
 *
 * (c) 2023 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Feature;

use Inertia\Controllers\TestController;
use Tests\Support\FeatureControllerTestCase;

uses(FeatureControllerTestCase::class);

describe('Controller Tests', function () {
    it('is return ok', function () {
        /**
         * @var FeatureControllerTestCase $this
         */
        $result = $this->withUri('https://example.com')->controller(TestController::class)->execute('index');

        expect($result->isOK())->toBeTrue();
    });
});
