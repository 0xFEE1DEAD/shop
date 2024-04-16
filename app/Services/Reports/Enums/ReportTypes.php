<?php

namespace App\Services\Reports\Enums;

enum ReportTypes: string
{
    case JSON = 'json';
    case CSV = 'csv';
}