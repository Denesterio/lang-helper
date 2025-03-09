<?php

use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('app');
})->name('home')->middleware('auth');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('login');
})->name('login');

Route::prefix('api')->name('api.')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::post('/auth', [UserController::class, 'auth'])->name('auth');
        Route::post('/register', [UserController::class, 'store'])->name('store');
        Route::get('/get', [UserController::class, 'getUser'])->name('get');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
    });

    Route::middleware('auth')->group(function () {
        Route::apiResource('dictionary', DictionaryController::class);
        Route::apiResource('dictionary.word', WordController::class);
        Route::apiResource('dictionary.translation', TranslationController::class)->only(['update', 'destroy']);
        Route::get('language', [LanguageController::class, 'index'])->name('index');
    
        Route::prefix('process')->name('process.')->group(function () {
            Route::get('/buttons', [ProcessController::class, 'getButtons'])->name('buttons');
            Route::get('/words', [ProcessController::class, 'getWords'])->name('get');
            Route::get('/translations', [ProcessController::class, 'getTranslations'])->name('get');
        });
    });
});
