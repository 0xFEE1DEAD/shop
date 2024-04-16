<?php

namespace App\Services\Reports\Strategies;

use App\Services\Reports\Interfaces\DataMapInterface;
use App\Services\Reports\Interfaces\ReportGeneratorInterface;

class JsonReport implements ReportGeneratorInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected DataMapInterface $dataMap
    )
    {
        //
    }

    public function handle($stream): void
    {
        $array = [];
        $columnNames = $this->dataMap->getColumnNames();
        $columnNamesLength = count($columnNames);

        foreach ($this->dataMap->getDataGenerator() as $fields) {
            $slicedFields = array_slice($fields, 0, $columnNamesLength);
            $item = [];
            foreach ($slicedFields as $index => $field) {
                $item[$columnNames[$index]] = $field;
            }
            $array[] = $item;
        }

        fwrite($stream, json_encode($array));
    }
}
