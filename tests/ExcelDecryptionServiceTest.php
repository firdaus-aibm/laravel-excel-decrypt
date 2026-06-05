<?php

namespace FirdausAibm\LaravelExcelDecrypt\Tests;

use FirdausAibm\LaravelExcelDecrypt\ExcelDecryptionService;
use FirdausAibm\LaravelExcelDecrypt\Exceptions\ExcelDecryptException;
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

    /** @test */
    public function it_throws_when_encrypted_file_is_missing()
    {
        $service = app(ExcelDecryptionService::class);

        $this->expectException(ExcelDecryptException::class);
        $this->expectExceptionMessage('Encrypted file does not exist');

        $service->decryptFile(__DIR__ . '/non-existent-file.xlsx', 'password');
    }

    /** @test */
    public function it_honors_max_file_size_limit()
    {
        $service = app(ExcelDecryptionService::class);

        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        file_put_contents($tempFile, str_repeat('A', 1024));

        config()->set('excel-decrypt.max_file_size', 10); // bytes

        $this->expectException(ExcelDecryptException::class);
        $this->expectExceptionMessage('exceeds maximum allowed size');

        try {
            $service->decryptFile($tempFile, 'password');
        } finally {
            @unlink($tempFile);
        }
    }

    /** @test */
    public function it_cleans_up_all_decrypted_files()
    {
        $service = app(ExcelDecryptionService::class);

        $tempDir = $service->getTempDirectory();
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $paths = [];
        for ($i = 0; $i < 3; $i++) {
            $path = $tempDir . '/decrypted_test_' . $i . '.xlsx';
            file_put_contents($path, 'test');
            $paths[] = $path;
        }

        foreach ($paths as $path) {
            $this->assertFileExists($path);
        }

        $service->cleanupAllDecryptedFiles();

        foreach ($paths as $path) {
            $this->assertFileDoesNotExist($path);
        }
    }
} 