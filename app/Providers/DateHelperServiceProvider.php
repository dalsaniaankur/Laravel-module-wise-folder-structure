<?php
namespace App\Providers;

use Illuminate\Support\Facades\App; //new
use Illuminate\Support\ServiceProvider;

class DateHelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Date helper bind with applicaiton
		App::bind('DateHelper', function()
        {
            return new \App\Helpers\DateHelper;
        });
    }
}
