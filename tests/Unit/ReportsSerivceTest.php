<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Reports\Interfaces\DataMapInterface;
use App\Services\Reports\ReportService;
use App\Services\Reports\Enums\ReportTypes;

class ReportsSerivceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_csv_stream_to_output(): void
    {
        $reportService = new ReportService(
            $this->createDataMapStub()
        );
        $result = $reportService->getByType(ReportTypes::CSV);

        ob_start();
        $result->streamToClient();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("\"Column 1\",\"Column 2\"\n\"Value 1\",\"Value 2\"\n\"Value 3\",\"Value 4\"\n", $content);
    }

    public function test_csv_string(): void
    {
        $reportService = new ReportService(
            $this->createDataMapStub()
        );
        $result = $reportService->getByType(ReportTypes::CSV);
        
        $this->assertEquals("\"Column 1\",\"Column 2\"\n\"Value 1\",\"Value 2\"\n\"Value 3\",\"Value 4\"", (string)$result);
    }

    public function test_json_stream_to_output(): void
    {
        $reportService = new ReportService(
            $this->createDataMapStub()
        );
        $result = $reportService->getByType(ReportTypes::JSON);

        ob_start();
        $result->streamToClient();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('[{"Column 1":"Value 1","Column 2":"Value 2"},{"Column 1":"Value 3","Column 2":"Value 4"}]', $content);
    }

    private function createDataMapStub(): DataMapInterface
    {
        $dataMapStub = $this->createStub(DataMapInterface::class);
        $dataMapStub->method('getColumnNames')->willReturn([
            'Column 1',
            'Column 2'
        ]);
        $dataMapStub->method('getDataGenerator')->willReturn(
            $this->createGenerator([
                ['Value 1', 'Value 2'],
                ['Value 3', 'Value 4']
            ])
        );
        return $dataMapStub;
    }
    private function createGenerator(array $data): \Generator
    {
        foreach ($data as $value) {
            yield $value;
        }
    }
}
