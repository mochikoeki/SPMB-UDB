<?php

use Illuminate\Support\Facades\Route;
use Filament\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

