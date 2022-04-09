<?php

use App\Http\Controllers\SubscriptionController;
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

        Route::group(["prefix" => "students"], function () {
            Route::get("/", [\App\Http\Controllers\StudentController::class, 'index'])->name('dashboard.students');

            Route::get("/list", [\App\Http\Controllers\StudentController::class, 'list'])->name('dashboard.list');

            Route::get("/{id}", [\App\Http\Controllers\StudentController::class, 'show'])->name('students.show');

            Route::post("/", [\App\Http\Controllers\StudentController::class, 'store'])->name('students.store');

            Route::put("/{id}", [\App\Http\Controllers\StudentController::class, 'update'])->name('students.update');

            Route::delete("/{id}", [\App\Http\Controllers\StudentController::class, 'destroy'])->name('students.destroy');
        });

        Route::group(["prefix" => "plans"], function () {
            Route::get("/", [\App\Http\Controllers\PlanController::class, 'index'])->name('plans.index');

            Route::get("/list", [\App\Http\Controllers\PlanController::class, 'list'])->name('plans.list');

            Route::get("/{id}", [\App\Http\Controllers\PlanController::class, 'show'])->name('plans.show');

            Route::post("/", [\App\Http\Controllers\PlanController::class, 'store'])->name('plans.store');

            Route::put("/{id}", [\App\Http\Controllers\PlanController::class, 'update'])->name('plans.update');

            Route::delete("/{id}", [\App\Http\Controllers\PlanController::class, 'destroy'])->name('plans.destroy');
        });

        Route::group(["prefix" => "subscriptions"], function () {
            Route::get("/", [SubscriptionController::class, 'index'])->name('subscriptions.index');

            Route::get("/url", [SubscriptionController::class, 'url'])->name('subscriptions.url');

            Route::get("/list", [SubscriptionController::class, 'list'])->name('subscriptions.list');

            Route::get("/{subscription}", [SubscriptionController::class, 'show'])->name('subscriptions.show');

            Route::post("/", [SubscriptionController::class, 'store'])->name('subscriptions.store');

            Route::put("/{subscription}", [SubscriptionController::class, 'update'])->name('subscriptions.update');

            Route::delete("/{subscription}", [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
        });

    });
});
