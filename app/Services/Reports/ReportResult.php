<?php

namespace App\Services\Reports;

use App\Services\Reports\Interfaces\ReportGeneratorInterface;
use Stringable;

class ReportResult implements Stringable
{
    public function __construct(
        private ReportGeneratorInterface $reportGenerator
    ) {
    }

    public function writeToStream($stream): void
    {
        $this->reportGenerator->handle($stream);
    }

    public function streamToClient(): void
    {
        $stream = fopen('php://output', 'w');
        $this->reportGenerator->handle($stream);
        fclose($stream);
    }

    public function __toString(): string
    {
        $memory = fopen('php://memory', 'r+');
        $this->reportGenerator->handle($memory);
        rewind($memory);
        return rtrim(stream_get_contents($memory));
    }
}