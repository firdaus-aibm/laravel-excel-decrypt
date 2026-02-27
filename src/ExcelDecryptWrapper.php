<?php

namespace FirdausAibm\LaravelExcelDecrypt;

use FirdausAibm\LaravelExcelDecrypt\Exceptions\ExcelDecryptException;

class ExcelDecryptWrapper
{
    /**
     * Ensure the underlying decryption library is available.
     */
    private static function ensureLibraryLoaded(): void
    {
        $path = __DIR__ . '/../lib/PHPDecryptXLSXWithPasswordCustom.php';

        if (!file_exists($path)) {
            throw ExcelDecryptException::decryptionFailed('Decryption library file not found.');
        }

        require_once $path;
    }

    /**
     * Decrypt an Excel file using the external library
     */
    public static function decryptExcelFile(string $encryptedFilePath, string $password, string $decryptedFilePath): void
    {
        self::ensureLibraryLoaded();

        // Call the custom decrypt function from the external library
        \decryptExcelFile($encryptedFilePath, $password, $decryptedFilePath);
    }
} 