<?php

namespace FirdausAibm\LaravelExcelDecrypt;

use Illuminate\Support\Facades\Storage;
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
        if ($maxFileSize && filesize($encryptedFilePath) > $maxFileSize) {
            throw ExcelDecryptException::fileTooLarge(filesize($encryptedFilePath), $maxFileSize);
        }

        // Create a temporary file for the decrypted version
        $tempDir = config('excel-decrypt.temp_directory', storage_path('app/temp'));
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $decryptedFilePath = $tempDir . '/' . uniqid('decrypted_') . '.xlsx';

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