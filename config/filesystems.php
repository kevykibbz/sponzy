<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'default' => [
            'driver' => 'local',
            'root' => public_path(),
            'url' => env('APP_URL').'/public',
            'visibility' => 'public',
       ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'dospace' => [
            'driver' => 's3',
            'key' => env('DOS_ACCESS_KEY_ID'),
            'secret' => env('DOS_SECRET_ACCESS_KEY'),
            'region' => env('DOS_DEFAULT_REGION'),
            'bucket' => env('DOS_BUCKET'),
            'visibility' => 'public',
            'endpoint' => 'https://'.env('DOS_DEFAULT_REGION').'.digitaloceanspaces.com',
        ],
  
        'wasabi' => [
            'driver' => 's3',
            'key' => env('WAS_ACCESS_KEY_ID'),
            'secret' => env('WAS_SECRET_ACCESS_KEY'),
            'region' => env('WAS_DEFAULT_REGION'),
            'bucket' => env('WAS_BUCKET'),
            'visibility' => 'public',
            'endpoint' => 'https://s3.'.env('WAS_DEFAULT_REGION').'.wasabisys.com'
        ],
  
        'backblaze' => [
            'driver'         => 'b2',
            'accountId'      => env('BACKBLAZE_ACCOUNT_ID'),
            'applicationKey' => env('BACKBLAZE_APP_KEY'),
            'bucketName'     => env('BACKBLAZE_BUCKET'),
            'bucketId'       => env('BACKBLAZE_BUCKET_ID'),
        ],
  
        'vultr' => [
            'driver' => 's3',
            'key'=> env('VULTR_ACCESS_KEY'),
            'secret' => env('VULTR_SECRET_KEY'),
            'region' => env('VULTR_REGION'),
            'bucket' => env('VULTR_BUCKET'),
            'visibility' => 'public',
            'endpoint' => env('VULTR_ENDPOINT'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
