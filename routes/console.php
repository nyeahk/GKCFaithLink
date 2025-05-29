<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('route:clear-and-cache', function () {
    $this->call('route:clear');
    $this->info('Route cache cleared!');
    $this->call('route:cache');
    $this->info('Routes cached successfully!');
})->purpose('Clear and recache routes');


