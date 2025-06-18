<?php

namespace Modules\Schooling\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\Corporation\Models\Announcement;
use Modules\Corporation\Models\Report;
use Modules\Corporation\Models\Stock;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void {}

    /**
     * Get the services provided by the provider.
     */
    public function boot()
    {
        View::composer('*', function ($view) {

            $view->with([]);
        });
    }
}
