<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(["middleware" => "guest"], function () {
    Route::get("/register", [\App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::post("/register", [\App\Http\Controllers\Auth\AuthController::class, 'makeRegister']);

    Route::get("/login", [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name("login");
    Route::post("/login", [\App\Http\Controllers\Auth\AuthController::class, 'makeLogin']);

});



Route::group(["middleware" => "auth:web"], function () {
    Route::get("/logout", [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name("logout");

    Route::group(["prefix" => "dashboard"], function () {
        Route::get("/students", [\App\Http\Controllers\StudentController::class, 'index'])->name('dashboard.students');

    });
});
