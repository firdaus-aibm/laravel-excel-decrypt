<?php

namespace FirdausAibm\LaravelExcelDecrypt;

use Illuminate\Support\Facades\Storage;

class ExcelDecryptionService
{
    /**
     * Decrypt an Excel file with password and return the decrypted file path
     */
    public function decryptFile(string $encryptedFilePath, string $password): string
    {
        // Create a temporary file for the decrypted version
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $decryptedFilePath = $tempDir . '/' . uniqid('decrypted_') . '.xlsx';

        try {
            // Use the wrapper to avoid naming conflicts
            ExcelDecryptWrapper::decryptExcelFile($encryptedFilePath, $password, $decryptedFilePath);

            if (!file_exists($decryptedFilePath)) {
                throw new \RuntimeException('Failed to decrypt the file. Please check if the password is correct.');
            }

            return $decryptedFilePath;
        } catch (\Exception $e) {
            // Clean up the temp file if it was created
            if (file_exists($decryptedFilePath)) {
                unlink($decryptedFilePath);
            }
            throw new \RuntimeException('Failed to decrypt the Excel file: ' . $e->getMessage());
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
} 