<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SuperAdmin\PermissionController;
use App\Http\Controllers\SuperAdmin\MenuController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\TranslationController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/super_admin')->name('super_admin.')->group(function () {
        Route::resource('permission', PermissionController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware(['permission:read permission']);

        Route::resource('role', RoleController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware(['permission:read role']);

        Route::patch('/role/{role}/detach/{permission}', [RoleController::class, 'detach'])->name('role.detach')->middleware(['permission:update role']);

        Route::resource('user', UserController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware(['permission:read user']);

        Route::prefix('/user/{user}')->name('user.')->controller(UserController::class)->middleware(['permission:update user'])->group(function () {
            Route::patch('/role/{role}/detach', 'detachRole')->name('role.detach');
            Route::patch('/permission/{permission}/detach', 'detachPermission')->name('permission.detach');
        });

        Route::patch('/menu/save', [MenuController::class, 'save'])->name('menu.save')->middleware(['permission:update menu']);
        Route::resource('menu', MenuController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware(['permission:read menu']);
        Route::get('/menu/{menu}/counter', [MenuController::class, 'counter'])->name('menu.counter');

        Route::prefix('/translation')->name('translation.')->controller(TranslationController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/', 'update')->name('update');
        });

        Route::get('/activity/login', [ActivityController::class, 'login'])->name('activity.login');

        Route::get('/user/{user}/menu', fn(User $user) => $user->menus())->name('user.menu');
        Route::get('/permission/get', [PermissionController::class, 'get'])->name('permission');
        Route::get('/role/get', [RoleController::class, 'get'])->name('role');
        Route::post('/role/paginate', [RoleController::class, 'paginate'])->name('role.paginate');
        Route::post('/user/paginate', [UserController::class, 'paginate'])->name('user.paginate');
        Route::post('/activity/login', [ActivityController::class, 'logins'])->name('activity.login');
        Route::get('/menu/get', [MenuController::class, 'get'])->name('menu');
    });
});
