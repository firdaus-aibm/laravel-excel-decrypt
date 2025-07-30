<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Excel Decrypt Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Excel Decrypt package.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Temporary Directory
    |--------------------------------------------------------------------------
    |
    | The directory where decrypted files will be temporarily stored.
    |
    */
    'temp_directory' => storage_path('app/temp'),

    /*
    |--------------------------------------------------------------------------
    | File Cleanup
    |--------------------------------------------------------------------------
    |
    | Whether to automatically clean up decrypted files after use.
    |
    */
    'auto_cleanup' => true,

    /*
    |--------------------------------------------------------------------------
    | Maximum File Size
    |--------------------------------------------------------------------------
    |
    | Maximum file size in bytes that can be decrypted.
    | Set to null for no limit.
    |
    */
    'max_file_size' => null,
]; 