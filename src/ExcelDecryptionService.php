<?php

namespace FirdausAibm\LaravelExcelDecrypt;

use FirdausAibm\LaravelExcelDecrypt\Exceptions\ExcelDecryptException;

class ExcelDecryptionService
{
    /**
     * Decrypt an Excel file with password and return the decrypted file path
     */
    public function decryptFile(string $encryptedFilePath, string $password): string
    {
        // Validate file exists
        if (!file_exists($encryptedFilePath)) {
            throw ExcelDecryptException::fileNotFound($encryptedFilePath);
        }

        // Check file size if configured
        $maxFileSize = config('excel-decrypt.max_file_size');
        $size = filesize($encryptedFilePath);

        if ($maxFileSize !== null && $maxFileSize > 0 && $size > $maxFileSize) {
            throw ExcelDecryptException::fileTooLarge($size, $maxFileSize);
        }

        // Create a temporary file for the decrypted version
        $tempDir = config('excel-decrypt.temp_directory', storage_path('app/temp'));

        if (!is_dir($tempDir) && !mkdir($tempDir, 0755, true) && !is_dir($tempDir)) {
            throw ExcelDecryptException::tempDirectoryNotWritable($tempDir);
        }

        if (!is_writable($tempDir)) {
            throw ExcelDecryptException::tempDirectoryNotWritable($tempDir);
        }

        $decryptedFilePath = $tempDir . '/' . uniqid('decrypted_', true) . '.xlsx';

        try {
            // Use the wrapper to avoid naming conflicts
            ExcelDecryptWrapper::decryptExcelFile($encryptedFilePath, $password, $decryptedFilePath);

            if (!file_exists($decryptedFilePath)) {
                throw ExcelDecryptException::invalidPassword();
            }

            return $decryptedFilePath;
        } catch (\Exception $e) {
            // Clean up the temp file if it was created
            if (file_exists($decryptedFilePath)) {
                unlink($decryptedFilePath);
            }
            throw ExcelDecryptException::decryptionFailed($e->getMessage());
        }
    }

    /**
     * Decrypt an Excel file, execute a callback with the decrypted path,
     * and optionally clean up the decrypted file afterwards.
     *
     * @template T
     * @param callable(string):T $callback
     * @return T
     */
    public function withDecryptedFile(string $encryptedFilePath, string $password, callable $callback)
    {
        $path = $this->decryptFile($encryptedFilePath, $password);

        try {
            /** @var T */
            $result = $callback($path);
        } finally {
            if (config('excel-decrypt.auto_cleanup', true)) {
                $this->cleanupDecryptedFile($path);
            }
        }

        return $result;
    }

    /**
     * Clean up decrypted file
     */
    public function cleanupDecryptedFile(string $decryptedFilePath): void
    {
        if (file_exists($decryptedFilePath)) {
            unlink($decryptedFilePath);
        }
    }

    /**
     * Clean up all decrypted files in temp directory
     */
    public function cleanupAllDecryptedFiles(): void
    {
        $tempDir = config('excel-decrypt.temp_directory', storage_path('app/temp'));
        
        if (is_dir($tempDir)) {
            $files = glob($tempDir . '/decrypted_*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Get the temporary directory path
     */
    public function getTempDirectory(): string
    {
        return config('excel-decrypt.temp_directory', storage_path('app/temp'));
    }
} 