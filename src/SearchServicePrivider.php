<?php

namespace Denismitr\Search;

use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{

    public function boot()
    {

    }

    public function register()
    {
        $this->app['search'] = $this->app->share(function($app) {
            return new Search;
        });
    }
}