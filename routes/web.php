<?php
use App\Http\Controllers\PointsController;
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

Route::get('/points/all', [PointsController::class, 'all'])->name('points.all');
Route::get('/point/{id}/delete', [PointsController::class, 'delete'])->name('points.delete');
Route::resource('/points', PointsController::class);
