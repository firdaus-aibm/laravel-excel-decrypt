<?php

namespace FirdausAibm\LaravelExcelDecrypt\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string decryptFile(string $encryptedFilePath, string $password)
 * @method static void cleanupDecryptedFile(string $decryptedFilePath)
 * @method static void cleanupAllDecryptedFiles()
 * @method static string getTempDirectory()
 *
 * @see \FirdausAibm\LaravelExcelDecrypt\ExcelDecryptionService
 */
class ExcelDecrypt extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'excel-decrypt';
    }
} 