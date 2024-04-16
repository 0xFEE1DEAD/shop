<?php

namespace App\Services\Reports\Strategies;

use App\Services\Reports\Interfaces\DataMapInterface;
use App\Services\Reports\Interfaces\ReportGeneratorInterface;
use App\Services\Reports\Exceptions\Generator\CsvGeneratorException;

class CsvReport implements ReportGeneratorInterface
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
        $columnNames = $this->dataMap->getColumnNames();
        $columnNamesLength = count($columnNames);
        if(fputcsv($stream, $columnNames) === false) {
            throw new CsvGeneratorException('Stream is not available');
        }

        foreach ($this->dataMap->getDataGenerator() as $fields) {
            $slicedFields = array_slice($fields, 0, $columnNamesLength);
            if(fputcsv($stream, $slicedFields) === false) {
                throw new CsvGeneratorException('Stream is not available');
            }
        }
    }
}
