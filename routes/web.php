<?php

use Illuminate\Support\Facades\Route;

Route::get('/', '\App\Services\Parsers\ParseBauinvestTest@parse');
