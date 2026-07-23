<?php

namespace App\Providers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('google', function ($app, array $config) {
            $client = new Client();
            $client->setAuthConfig($config['credentials_path']);
            $client->addScope(Drive::DRIVE);

            $options = [];
            if (!empty($config['team_drive_id'])) {
                $options['teamDriveId'] = $config['team_drive_id'];
            } elseif (!empty($config['shared_folder_id'])) {
                $options['sharedFolderId'] = $config['shared_folder_id'];
            }

            $service = new Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folder'] ?? null, $options);
            $driver = new Filesystem($adapter);

            return new FilesystemAdapter($driver, $adapter, $config);
        });
    }
}
