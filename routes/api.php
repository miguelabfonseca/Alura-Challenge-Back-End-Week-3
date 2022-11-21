<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CategoriesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
], function($router) {
    Route::resource('categories', CategoriesController::class);
    Route::resource('videos', VideoController::class);
    Route::get('/categories/{id}/videos', [CategoriesController::class, 'videos']);
    Route::post('apilogin', [LoginController::class, 'login'])->name('login');
});
