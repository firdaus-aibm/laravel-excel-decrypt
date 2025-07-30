<?php

namespace FirdausAibm\LaravelExcelDecrypt;

class ExcelDecryptWrapper
{
    /**
     * Decrypt an Excel file using the external library
     */
    public static function decryptExcelFile(string $encryptedFilePath, string $password, string $decryptedFilePath): void
    {
        // Include the custom version of the external library
        require_once __DIR__ . '/../lib/PHPDecryptXLSXWithPasswordCustom.php';

        // Call the custom decrypt function from the external library
        \decryptExcelFile($encryptedFilePath, $password, $decryptedFilePath);
    }
} 