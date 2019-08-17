<?php

namespace Zuweie\Setting;

use Illuminate\Support\ServiceProvider;
use Encore\Admin\Admin;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Setting $extension)
    {
        if (! Setting::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'setting');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            
            // publish assets
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/setting')],
                'laravel-admin-ext-setting-assets'
            );
            // publish database
            $this->publishes([__DIR__.'/../database/migrations/' => database_path('migrations')], 'laravel-admin-ext-setting-migrations');
            
        }

        $this->app->booted(function () {
            Setting::routes(__DIR__.'/../routes/web.php');
        });
    }
}