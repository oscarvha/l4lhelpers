<?php

namespace Osd\L4lHelpers;

use Illuminate\Support\ServiceProvider;
use Osd\L4lHelpers\IP\Application\GetIpLookup;
use Osd\L4lHelpers\IP\Console\IPLookup;
use Osd\L4lHelpers\IP\Domain\Contracts\UuidGenerator;
use Osd\L4lHelpers\IP\Domain\Contracts\IpProvider;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupConfigRepository;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupRepository;
use Osd\L4lHelpers\IP\Infrastructure\IpGuideProvider;
use Osd\L4lHelpers\IP\Infrastructure\LaravelIpLookupConfigRepository;
use Osd\L4lHelpers\IP\Infrastructure\Persistence\EloquentIpLookupRepository;
use Osd\L4lHelpers\IP\Infrastructure\SymfonyUuidGenerator;
use Osd\L4lHelpers\Mail\Console\MailCheck;


class L4lHelpersServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IpProvider::class, IpGuideProvider::class);
        $this->app->bind(UuidGenerator::class, SymfonyUuidGenerator::class);

        $this->app->bind(IpLookupRepository::class, EloquentIpLookupRepository::class);

        $this->app->bind(IpLookupConfigRepository::class, LaravelIpLookupConfigRepository::class);


        $this->app->singleton(GetIpLookup::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MailCheck::class,
                IPLookup::class,
            ]);

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'l4lhelpers');


            $this->publishes([
                __DIR__.'/../config/l4lhelpers.php' => config_path('l4lhelpers.php'),
            ], 'l4lhelpers');

        }
    }
}
