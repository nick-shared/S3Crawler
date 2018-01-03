<?php

namespace Mutant\S3Crawler\App\Providers;

use Illuminate\Support\ServiceProvider;
use Mutant\S3Crawler\App\Console\Commands\S3BucketCrawlerCommand;

/**
 * Class S3CrawlerProvider
 * @package Mutant\S3Crawler\App\Providers
 */
class S3CrawlerProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        // Register config file
        $this->publishes([
            __DIR__ . '/../../config/s3crawler.php' => config_path('s3crawler.php'),
        ]);

        // Register migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                S3BucketCrawlerCommand::class
            ]);
        }
    }
}
