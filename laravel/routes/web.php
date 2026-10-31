<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/about', [IndexController::class, 'about'])->name('about');
Route::get('/regulations', [IndexController::class, 'regulations'])->name('regulations');
Route::redirect('/index', '/');
Route::permanentRedirect('/regulamin', '/regulations');

Route::get('/pets', [PetController::class, 'index'])->name('pets.index');
Route::redirect('/pets/dogs', '/pets?species=dog');
Route::redirect('/pets/cats', '/pets?species=cat');
Route::get('/pets/{pet}', [PetController::class, 'show'])->whereNumber('pet')->name('pets.show');

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/auth/login', 'login')->name('login');
    Route::post('/auth/login', 'authenticate')->middleware('throttle:5,1')->name('login.authenticate');
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/pets/{pet}/adopt', [AdoptionController::class, 'adopt'])->name('adopt');
    Route::post('/pets/{pet}/reserve', [AdoptionController::class, 'reserve'])->name('reserve');
    Route::post('/adoptions/{adoption}/cancel', [AdoptionController::class, 'cancel'])->name('adoptions.cancel');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/user/profile', 'index')->name('user.profile');
        Route::get('/user/edit', 'edit')->name('user.edit');
        Route::put('/user/update', 'update')->name('user.update');
        Route::put('/user/change-password', 'changePassword')->name('user.change_password');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/adoptions')->name('index');

    Route::get('/adoptions', [Admin\AdoptionController::class, 'index'])->name('adoptions');
    Route::post('/adoptions/{adoption}/status', [Admin\AdoptionController::class, 'updateStatus'])->name('adoptions.updateStatus');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/pets', [Admin\PetController::class, 'index'])->name('pets');
    Route::get('/pets/create', [Admin\PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [Admin\PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{pet}/edit', [Admin\PetController::class, 'edit'])->name('pets.edit');
    Route::put('/pets/{pet}', [Admin\PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [Admin\PetController::class, 'destroy'])->name('pets.destroy');

    Route::get('/vaccinations', [Admin\VaccinationController::class, 'index'])->name('vaccinations');
    Route::post('/vaccinations', [Admin\VaccinationController::class, 'store'])->name('vaccinations.store');
    Route::delete('/vaccinations/{vaccination}', [Admin\VaccinationController::class, 'destroy'])->name('vaccinations.destroy');
});
