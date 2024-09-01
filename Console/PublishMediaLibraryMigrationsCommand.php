<?php

namespace Modules\Settings\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class PublishMediaLibraryMigrationsCommand extends Command
{
    protected $signature    = 'settings:publish-media-migrations';
    protected $description  = 'Publish media library migrations to the module';

    public function handle()
    {
        try{
            // $response=Artisan::call('vendor:publish', [
            //     '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
            //     '--tag' => 'medialibrary-migrations',
            // ]);

            $response=Artisan::call('vendor:publish', [
                '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                '--tag' => 'migrations',
            ]);

            Log::info('vendor publish done ');
            Log::info($response);
        }catch(\Exception $e){
            Log::info('vendor publish error');
            Log::info($e->getMessage());
        }

        $this->moveMediaMigrations();

        $this->info('Media library migrations published to the Settings module.');
    }

    protected function moveMediaMigrations()
    {
        Log::info('inside moveMediaMigrations');
        Log::info(database_path('migrations/*create_media_table.php'));
        Log::info('glob path');
        // $migrationFiles = glob(database_path('migrations/*create_media_table.php'));
        $migrationFiles = glob(database_path('migrations/*create_media_table.php'));
        Log::info($migrationFiles);

        foreach ($migrationFiles as $file) {
            $newPath = module_path('Settings', 'Database/Migrations/' . basename($file));
            rename($file, $newPath);
        }
    }
}