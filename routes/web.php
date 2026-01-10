<?php

use Illuminate\Support\Facades\Route;

// ========================================
// VUE SPA ROUTE - Catch all untuk Vue Router
// ========================================
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$')->name('spa');
