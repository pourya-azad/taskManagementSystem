<?php

use App\Events\TaskUpdated;
use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\LivewireAlert;

Route::get('/', function () {
    return view('dashboard');
});



