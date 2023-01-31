<?php

namespace Aqayepardakht\Logger;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Aqayepardakht\Logger\Contracts\ClearableRepository;
use Aqayepardakht\Logger\Contracts\EntriesRepository;
use Aqayepardakht\Logger\Contracts\PrunableRepository;
use Aqayepardakht\Logger\Storage\DatabaseEntriesRepository;

class TelescopeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishing();

        if (! config('telescope.enabled')) {
            return;
        }
        Telescope::start($this->app);
        Telescope::listenForStorageOpportunities($this->app);
        $this->hideSensitiveRequestDetails();
        
        //Route::middlewareGroup('telescope', config('telescope.middleware', []));
        // $this->authorization();
        // Telescope::filter(function (IncomingEntry $entry) {
        //     if ($this->app->environment('local')) {
        //         return true;
        //     }

        //     return $entry->isReportableException() ||
        //            $entry->isFailedRequest() ||
        //            $entry->isFailedJob() ||
        //            $entry->isScheduledTask() ||
        //            $entry->hasMonitoredTag();
        // });
    }

    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    private function routeConfiguration()
    {
        return [
            'domain' => config('telescope.domain', null),
            'namespace' => 'Aqayepardakht\Logger\Http\Controllers',
            'prefix' => config('telescope.path'),
            'middleware' => 'telescope',
        ];
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/telescope.php' => config_path('telescope.php'),
            ], 'telescope');

        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/telescope.php', 'telescope'
        );

        $this->registerStorageDriver();
    }

    protected function registerStorageDriver()
    {
        $driver = config('telescope.driver');

        if (method_exists($this, $method = 'register'.ucfirst($driver).'Driver')) {
            $this->$method();
        }
    }

    protected function registerDatabaseDriver()
    {
        $this->app->singleton(
            EntriesRepository::class, DatabaseEntriesRepository::class
        );

        $this->app->singleton(
            ClearableRepository::class, DatabaseEntriesRepository::class
        );

        $this->app->singleton(
            PrunableRepository::class, DatabaseEntriesRepository::class
        );

        $this->app->when(DatabaseEntriesRepository::class)
            ->needs('$connection')
            ->give(config('telescope.storage.database.connection'));

        $this->app->when(DatabaseEntriesRepository::class)
            ->needs('$chunkSize')
            ->give(config('telescope.storage.database.chunk'));
    }

     // protected function authorization()
    // {
    //     $this->gate();

    //     Telescope::auth(function ($request) {
    //         return app()->environment('local') ||
    //                Gate::check('viewTelescope', [$request->user()]);
    //     });
    // }

    // protected function gate()
    // {
    //     Gate::define('viewTelescope', function ($user) {
    //         return in_array($user->email, [
    //             //
    //         ]);
    //     });
    // }
}
