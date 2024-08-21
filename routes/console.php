<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//Artisan::call('queue:work --queue=critical --stop-when-empty')->everyFiveSeconds();

//Artisan::call('queue:work --queue=default --stop-when-empty')->everyFifteenSeconds();
