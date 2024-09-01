<?php

namespace Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Settings\Http\Traits\Configuration;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    use Configuration;
    //===================================================================================
    protected $moduleName = 'Settings';
    protected $moduleNameLower = 'settings';
    //===================================================================================
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom($this->module_path($this->moduleName, 'Database/Migrations'));
            //==============================================================================================
        // publish all package folder
        $this->publishes([
            dirname(__DIR__) .'/' => base_path('Modules/Settings')        
        ], 'settings-module');
        //==============================================================================================
        // publish config
        $this->publishes([
            dirname(__DIR__) .'/Config/config.php' => config_path('settings.php'),
        ], 'settings-config');
        //==============================================================================================
   }
    //===================================================================================
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(MediaLibraryServiceProvider::class);
    }
    //===================================================================================
    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void{
        $this->commands([
            \Modules\Settings\Console\PublishModuleCommand::class,
            \Modules\Settings\Console\PublishMediaLibraryMigrationsCommand::class,

        ]);
    }
    //===================================================================================

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            // $this->module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
            dirname(__DIR__).'/Config/config.php' => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            // $this->module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
            dirname(__DIR__).'/Config/config.php', $this->moduleNameLower
        );
    }
    //===================================================================================
    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = $this->module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }
    //===================================================================================
    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom($this->module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($this->module_path($this->moduleName, 'Resources/lang'));
        }
    }
    //===================================================================================
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
    //===================================================================================
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
    //===================================================================================
}
