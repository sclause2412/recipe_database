<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GlobalSettingsController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\RegistrationIsAllowed;
use App\Http\Middleware\UserIsElevated;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\RoutePath;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/forgot-username', [UserController::class, 'username'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('username.request');

Route::post('/forgot-username', [UserController::class, 'usernamesend'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('username.email');

Route::get('/forgot-email', [UserController::class, 'email'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('username.requestemail');

Route::post('/forgot-email', [UserController::class, 'emailsend'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('username.sendemail');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->withoutMiddleware('useractive')->name('logout.fast');

Route::get('/recipes/{recipe}/print', [RecipeController::class, 'print'])->name('recipes.print');
Route::resource('recipes', RecipeController::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->withoutMiddleware('useractive')->group(
    function () {
        Route::get('/locked', [UserController::class, 'locked'])->name('locked');
        Route::post('/locked', [UserController::class, 'unlock'])->name('locked.store');

        Route::get('/policy', [UserController::class, 'policy'])->name('policy');
        Route::post('/policy', [UserController::class, 'policy_accept'])->name('policy.accept');
    }
);


//Logged in
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/user/elevate', [UserController::class, 'elevate'])->name('user.elevate');
    Route::post('/user/elevate', [UserController::class, 'elevate_confirm'])->name('user.elevate.confirm');
    Route::get('/user/unelevate', [UserController::class, 'elevate_drop'])->name('user.unelevate');

    Route::get('/search/{area}/{model}', [SearchController::class, 'search'])->name('search');

    Route::resource('recipes', RecipeController::class)->except([
        'index',
        'show'
    ]);

    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('ingredients', IngredientController::class);


    Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::get('/translations/read', [TranslationController::class, 'read'])->name('translations.read');
    Route::get('/translations/write', [TranslationController::class, 'write'])->name('translations.write');
    Route::get('/translations/mode', [TranslationController::class, 'mode'])->name('translations.mode');
    Route::get('/translations/{locale}/edit', [TranslationController::class, 'edit'])->name('translations.edit');
    Route::get('/translations/{locale}/sync', [TranslationController::class, 'sync'])->name('translations.sync');
    Route::get('/translations/{locale}/import', [TranslationController::class, 'import'])->name('translations.import');
    Route::get('/translations/{locale}/export', [TranslationController::class, 'export'])->name('translations.export');

    Route::get('/textcode', function () {
        return view('textcode');
    })->name('textcode');
});

//Logged in as Admin
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    UserIsElevated::class,
])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/settings', [GlobalSettingsController::class, 'index'])->name('globalsettings');
});

Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome');

Route::get('/theme/style/{style}', [ThemeController::class, 'style'])->name('theme.style');
Route::get('/theme/font/{font}', [ThemeController::class, 'font'])->name('theme.font');
Route::get('/language/{locale}', [ThemeController::class, 'language'])->name('language');

Route::get('/image/o/{path}', [ImageController::class, 'orig'])->where(['path' => '[A-za-z0-9\-_/]+']);
Route::get('/image/w/{sizex}/{path}', [ImageController::class, 'width'])->where(['sizex' => '[1-9][0-9]{0,3}', 'path' => '[A-Za-z0-9\-_/]+']);
Route::get('/image/h/{sizey}/{path}', [ImageController::class, 'height'])->where(['sizey' => '[1-9][0-9]{0,3}', 'path' => '[A-Za-z0-9\-_/]+']);
Route::get('/image/f/{sizex}/{sizey}/{path}', [ImageController::class, 'fit'])->where(['sizex' => '[1-9][0-9]{0,3}', 'sizey' => '[1-9][0-9]{0,3}', 'path' => '[A-Za-z0-9\-_/]+']);
Route::get('/image/c/{sizex}/{sizey}/{path}', [ImageController::class, 'fill'])->where(['sizex' => '[1-9][0-9]{0,3}', 'sizey' => '[1-9][0-9]{0,3}', 'path' => '[A-Za-z0-9\-_/]+']);
Route::get('/image/s/{sizex}/{sizey}/{path}', [ImageController::class, 'stretch'])->where(['sizex' => '[1-9][0-9]{0,3}', 'sizey' => '[1-9][0-9]{0,3}', 'path' => '[A-Za-z0-9\-_/]+']);

Route::middleware([
    'guest:' . config('fortify.guard'),
    RegistrationIsAllowed::class,
])->group(function () {
    Route::get(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'create'])->name('register');
    Route::post(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'store']);
});
