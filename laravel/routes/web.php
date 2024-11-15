<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\IndexController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetController;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/index', [IndexController::class, 'index'])->name('index');
Route::get('/regulamin', [IndexController::class, 'regulamin'])->name('regulamin');
Route::get('/about', [IndexController::class, 'aboutUs'])->name('about');

Route::get('/pets/dogs', [PetController::class, 'showDogs'])->name('pets.dogs');
Route::get('/pets/cats', [PetController::class, 'showCats'])->name('pets.cats');
Route::get('/pets/{id}', [PetController::class, 'show'])->name('pets.show');
Route::get('/pets', [PetController::class, 'showAll'])->name('pets.all');

Route::post('/pets/adopt/{id}', [AdoptionController::class, 'adopt'])->name('adopt');
Route::post('/pets/reserve/{id}', [AdoptionController::class, 'reserve'])->name('reserve');

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/login', 'login')->name('login');
    Route::post('/auth/login', 'authenticate')->name('login.authenticate');
    Route::get('/auth/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::get('/profile/editData', [ProfileController::class, 'showSettings'])->name('editData');
    Route::put('/user/update', [ProfileController::class, 'update'])->name('user.update');
    Route::put('/user/change-password', [ProfileController::class, 'changePassword'])->name('user.change_password');
});


Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/adoptions', [AdminController::class, 'adoptions'])->name('admin.adoptions');
    Route::post('/admin/adoptions/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.adoptions.updateStatus');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/update/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::delete('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/admin/pets', [AdminController::class, 'pets'])->name('admin.pets');
    Route::get('/admin/pets/add', [AdminController::class, 'addPet'])->name('admin.addPet');
    Route::post('/admin/pets', [AdminController::class, 'storePet'])->name('admin.storePet');
    Route::get('/admin/pets/edit/{id}', [AdminController::class, 'editPet'])->name('admin.editPet');
    Route::post('/admin/pets/update/{id}', [AdminController::class, 'updatePet'])->name('admin.updatePet');
    Route::delete('/admin/pets/delete/{id}', [AdminController::class, 'deletePet'])->name('admin.deletePet');
    Route::get('/admin/vaccinations', [AdminController::class, 'showVaccinations'])->name('admin.vaccinations');
    Route::post('/admin/vaccinations', [AdminController::class, 'storeVaccination'])->name('admin.vaccinations.storeVaccination');
    Route::delete('/admin/vaccinations/{id}', [AdminController::class, 'destroyVaccination'])->name('admin.vaccinations.destroyVaccination');
    Route::get('/vaccinations/create', [AdminController::class, 'createVaccination'])->name('admin.vaccinations');
});
