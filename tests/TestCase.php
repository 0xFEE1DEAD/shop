<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function assertArrayValuesSequentially(array $array): void
    {
        $hasGap = false;
        
        $array = array_values($array);

        foreach ($array as $index => $value) {
            if ($value !== ($index + 1)) {
                $hasGap = true;
                break;
            }
        }

        $this->assertTrue(!$hasGap, 'Array have gap');
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
}
