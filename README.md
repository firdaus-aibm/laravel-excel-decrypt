# Laravel Excel Decrypt

A Laravel package for decrypting password-protected Excel files with support for file validation, size limits, and automatic cleanup.

## Installation

```bash
composer require firdaus-aibm/laravel-excel-decrypt
```

## Usage

### Using the Service

```php
use FirdausAibm\LaravelExcelDecrypt\ExcelDecryptionService;

$service = app(ExcelDecryptionService::class);

// Decrypt a file
$decryptedFilePath = $service->decryptFile('/path/to/encrypted.xlsx', 'password');

// Clean up the decrypted file when done
$service->cleanupDecryptedFile($decryptedFilePath);

// Or clean up all decrypted files
$service->cleanupAllDecryptedFiles();

### Using the Facade

```php
use FirdausAibm\LaravelExcelDecrypt\Facades\ExcelDecrypt;

// Decrypt a file
$decryptedFilePath = ExcelDecrypt::decryptFile('/path/to/encrypted.xlsx', 'password');

// Clean up the decrypted file when done
ExcelDecrypt::cleanupDecryptedFile($decryptedFilePath);

// Or clean up all decrypted files
ExcelDecrypt::cleanupAllDecryptedFiles();

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=excel-decrypt-config
```

This will create `config/excel-decrypt.php` with the following options:

- `temp_directory`: Directory where decrypted files are temporarily stored
- `auto_cleanup`: Whether to automatically clean up decrypted files
- `max_file_size`: Maximum file size that can be decrypted (in bytes)

## Error Handling

The package provides custom exceptions for better error handling:

```php
use FirdausAibm\LaravelExcelDecrypt\Exceptions\ExcelDecryptException;

try {
    $decryptedPath = ExcelDecrypt::decryptFile($path, $password);
} catch (ExcelDecryptException $e) {
    // Handle specific decryption errors
    echo $e->getMessage();
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information. 