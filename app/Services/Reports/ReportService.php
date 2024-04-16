<?php

namespace App\Services\Reports;

use App\Services\Reports\Interfaces\DataMapInterface;
use App\Services\Reports\Interfaces\ReportGeneratorInterface;
use App\Services\Reports\Strategies\CsvReport;
use App\Services\Reports\Strategies\JsonReport;
use App\Services\Reports\Enums\ReportTypes;

class ReportService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected DataMapInterface $dataMap
    ){
    }

    public function getByType(ReportTypes $reportType): ReportResult
    {
        return match($reportType) {
            ReportTypes::JSON => $this->response(new JsonReport($this->dataMap)),
            ReportTypes::CSV => $this->response(new CsvReport($this->dataMap)),
        };
    }

    protected function response(ReportGeneratorInterface $generator): ReportResult
    {
        return new ReportResult($generator);
    }
}
