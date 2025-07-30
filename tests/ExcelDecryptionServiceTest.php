<?php

namespace FirdausAibm\LaravelExcelDecrypt\Tests;

use FirdausAibm\LaravelExcelDecrypt\ExcelDecryptionService;
use Orchestra\Testbench\TestCase;

class ExcelDecryptionServiceTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \FirdausAibm\LaravelExcelDecrypt\LaravelExcelDecryptServiceProvider::class,
        ];
    }

    /** @test */
    public function it_can_be_resolved_from_container()
    {
        $service = app(ExcelDecryptionService::class);

        $this->assertInstanceOf(ExcelDecryptionService::class, $service);
    }

    /** @test */
    public function it_can_cleanup_decrypted_file()
    {
        $service = app(ExcelDecryptionService::class);

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        file_put_contents($tempFile, 'test content');

        $this->assertFileExists($tempFile);

        // Clean up the file
        $service->cleanupDecryptedFile($tempFile);

        $this->assertFileDoesNotExist($tempFile);
    }
} 