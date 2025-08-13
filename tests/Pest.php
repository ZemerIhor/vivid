<?php

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Pest configuration for Laravel
|--------------------------------------------------------------------------
|
| This file configures Pest to use Laravel's TestCase and applies it to the
| Feature and Unit test directories. You can add helpers, expectations,
| and functions here to be shared across your tests.
|
*/

uses(TestCase::class)->in('Feature', 'Unit');
