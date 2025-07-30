<?php

namespace FirdausAibm\LaravelExcelDecrypt\Exceptions;

use Exception;

class ExcelDecryptException extends Exception
{
    public static function fileNotFound(string $path): self
    {
        return new self("Encrypted file does not exist: {$path}");
    }

    public static function fileTooLarge(int $size, int $maxSize): self
    {
        return new self("File size ({$size} bytes) exceeds maximum allowed size ({$maxSize} bytes).");
    }

    public static function decryptionFailed(string $message): self
    {
        return new self("Failed to decrypt the Excel file: {$message}");
    }

    public static function invalidPassword(): self
    {
        return new self("Failed to decrypt the file. Please check if the password is correct.");
    }

    public static function tempDirectoryNotWritable(string $path): self
    {
        return new self("Temporary directory is not writable: {$path}");
    }
} 