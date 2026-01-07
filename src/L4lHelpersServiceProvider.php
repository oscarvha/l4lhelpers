<?php

namespace Osd\L4lHelpers;

use Illuminate\Support\ServiceProvider;
use Osd\L4lHelpers\Mail\Console\MailCheck;


class L4lHelpersServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MailCheck::class,
            ]);
        }
    }
}
