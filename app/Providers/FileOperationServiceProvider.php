<?php

namespace App\Providers;

use App\Facades\FileOperations;
use Illuminate\Support\ServiceProvider;

class FileOperationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('FileOperations',function()
        {
            return new  FileOperations();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
