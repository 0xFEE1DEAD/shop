<?php

namespace App\Services\Reports\Interfaces;

use Generator;

interface DataMapInterface
{
    public function getColumnNames(): array;
    public function getDataGenerator(): Generator;
}
