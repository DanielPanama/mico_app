<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
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

// Landing
Route::get('/', function () {
    return view('landing');
})->name('landing');


// Auth

Route::get('login', [AuthenticatedSessionController::class, 'login'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'storeLogin'])
    ->name('login.store')
    ->middleware('guest');

Route::get('register', [AuthenticatedSessionController::class, 'register'])
    ->name('register')
    ->middleware('guest');

Route::post('register', [AuthenticatedSessionController::class, 'storeRegister'])
    ->name('register.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard (Panel del Alumno)

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Grupos

Route::get('groups', [GroupsController::class, 'index'])
    ->name('groups')
    ->middleware('auth');

Route::get('groups/create', [GroupsController::class, 'create'])
    ->name('groups.create')
    ->middleware('auth');

Route::post('groups', [GroupsController::class, 'store'])
    ->name('groups.store')
    ->middleware('auth');

Route::get('groups/{group}/edit', [GroupsController::class, 'edit'])
    ->name('groups.edit')
    ->middleware('auth');

Route::put('groups/{group}', [GroupsController::class, 'update'])
    ->name('groups.update')
    ->middleware('auth');

Route::delete('groups/{group}', [GroupsController::class, 'destroy'])
    ->name('groups.destroy')
    ->middleware('auth');

Route::put('groups/{group}/restore', [GroupsController::class, 'restore'])
    ->name('groups.restore')
    ->middleware('auth');

// Actividades

Route::get('activities', [ActivitiesController::class, 'index'])
    ->name('activities')
    ->middleware('auth');

Route::get('activities/create', [ActivitiesController::class, 'create'])
    ->name('activities.create')
    ->middleware('auth');

Route::post('activities', [ActivitiesController::class, 'store'])
    ->name('activities.store')
    ->middleware('auth');

Route::get('activities/{activity}/edit', [ActivitiesController::class, 'edit'])
    ->name('activities.edit')
    ->middleware('auth');

Route::put('activities/{activity}', [ActivitiesController::class, 'update'])
    ->name('activities.update')
    ->middleware('auth');

Route::delete('activities/{activity}', [ActivitiesController::class, 'destroy'])
    ->name('activities.destroy')
    ->middleware('auth');

Route::put('activities/{activity}/restore', [ActivitiesController::class, 'restore'])
    ->name('activities.restore')
    ->middleware('auth');

// Reports

Route::get('actividades/{code}', [ReportsController::class, 'index'])
    ->name('actividades')
    ->middleware('guest');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
