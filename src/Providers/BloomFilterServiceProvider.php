<?php

namespace Jacksmall\Bloomfilter\Providers;

use Illuminate\Support\ServiceProvider;
use Jacksmall\Bloomfilter\Services\BloomFilter\BloomFilterManager;

class BloomFilterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/bloomfilter.php', 'bloomfilter'
        );

        $this->app->singleton('bloomfilter', function ($app) {
            $config = $app['config']['bloomfilter'];
            return new BloomFilterManager($config);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/bloomfilter.php' => config_path('bloomfilter.php'),
        ], 'bloomfilter-config');
    }
}