<?php

namespace App\Services\Reports\Interfaces;

interface ReportGeneratorInterface
{
    /**
     * Writes into stream report source
     *
     * @param resource $stream
     * @return void
     */
    public function handle($stream): void;
}
