<?php

namespace App\Services\Reports\Abstracts;

use App\Services\Reports\Interfaces\DataMapInterface;
use Generator;

abstract class DataMapAbstract implements DataMapInterface
{
    protected $colNames = [];
    public function setColumnNames(string ...$colNames): self
    {
        $this->colNames = $colNames;

        return $this;
    }
    public function getColumnNames(): array
    {
        return $this->colNames;
    }
    abstract public function getDataGenerator(): Generator;
}
