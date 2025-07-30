# Laravel Excel Decrypt

A Laravel package for decrypting password-protected Excel files.

## Installation

```bash
composer require aeon/laravel-excel-decrypt
```

## Usage

### Using the Service

```php
use Aeon\LaravelExcelDecrypt\ExcelDecryptionService;

$service = app(ExcelDecryptionService::class);

// Decrypt a file
$decryptedFilePath = $service->decryptFile('/path/to/encrypted.xlsx', 'password');

// Clean up the decrypted file when done
$service->cleanupDecryptedFile($decryptedFilePath);
```

### Using the Facade

```php
use Aeon\LaravelExcelDecrypt\Facades\ExcelDecrypt;

// Decrypt a file
$decryptedFilePath = ExcelDecrypt::decryptFile('/path/to/encrypted.xlsx', 'password');

// Clean up the decrypted file when done
ExcelDecrypt::cleanupDecryptedFile($decryptedFilePath);
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=excel-decrypt-config
```

This will create `config/excel-decrypt.php` with the following options:

- `temp_directory`: Directory where decrypted files are temporarily stored
- `auto_cleanup`: Whether to automatically clean up decrypted files
- `max_file_size`: Maximum file size that can be decrypted

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information. 