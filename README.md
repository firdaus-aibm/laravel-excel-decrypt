# Laravel Excel Decrypt

A Laravel package for decrypting password-protected Excel files with support for file validation, size limits, and temporary file cleanup.

## Requirements

- Laravel 11.x or 12.x
- PHP 8.2 or higher

## Installation

```bash
composer require firdaus-aibm/laravel-excel-decrypt
```

If you want to customize the configuration, publish the config file:

```bash
php artisan vendor:publish --tag=excel-decrypt-config
```

## Configuration

This will create `config/excel-decrypt.php` with the following options:

- `temp_directory` (string): Directory where decrypted files are temporarily stored. Defaults to `storage_path('app/temp')`.
- `auto_cleanup` (bool): Whether `withDecryptedFile()` should automatically remove the decrypted file after your callback runs. Defaults to `true`.
- `max_file_size` (int|null): Maximum file size (in bytes) that can be decrypted. `null` means no limit.

## Usage

### Using the service directly

```php
use FirdausAibm\LaravelExcelDecrypt\ExcelDecryptionService;

$service = app(ExcelDecryptionService::class);

// Decrypt a file and get the path to the decrypted copy
$decryptedFilePath = $service->decryptFile('/path/to/encrypted.xlsx', 'password');

// ... do something with $decryptedFilePath ...

// Manually clean up when you are done
$service->cleanupDecryptedFile($decryptedFilePath);

// Or clean up all decrypted files created by this package
$service->cleanupAllDecryptedFiles();
```

### Using the facade

```php
use FirdausAibm\LaravelExcelDecrypt\Facades\ExcelDecrypt;

// Decrypt a file
$decryptedFilePath = ExcelDecrypt::decryptFile('/path/to/encrypted.xlsx', 'password');

// Clean up the decrypted file when done
ExcelDecrypt::cleanupDecryptedFile($decryptedFilePath);

// Or clean up all decrypted files
ExcelDecrypt::cleanupAllDecryptedFiles();
```

### Using automatic cleanup with a callback

If you just want to work with the decrypted file and have it cleaned up automatically, use `withDecryptedFile`:

```php
use FirdausAibm\LaravelExcelDecrypt\ExcelDecryptionService;

$service = app(ExcelDecryptionService::class);

$result = $service->withDecryptedFile('/path/to/encrypted.xlsx', 'password', function (string $path) {
    // $path points to the decrypted temporary file
    // Process it (e.g. import rows, move it somewhere, etc.)

    return 'done';
});

// After the callback, the decrypted file is deleted if
// config('excel-decrypt.auto_cleanup') is true (default).
```

## Error handling

The package throws `FirdausAibm\LaravelExcelDecrypt\Exceptions\ExcelDecryptException` for common error cases:

- Encrypted file does not exist.
- File exceeds the configured `max_file_size`.
- Temporary directory cannot be created or is not writable.
- Decryption fails (for example, wrong password or missing underlying library).

Example:

```php
use FirdausAibm\LaravelExcelDecrypt\Facades\ExcelDecrypt;
use FirdausAibm\LaravelExcelDecrypt\Exceptions\ExcelDecryptException;

try {
    $decryptedPath = ExcelDecrypt::decryptFile($path, $password);
} catch (ExcelDecryptException $e) {
    // Handle specific decryption errors
    report($e);
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.