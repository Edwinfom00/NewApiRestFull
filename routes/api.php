<?php

use App\Http\Controllers\InternController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/intern', [InternController::class,'index']);
Route::post('/intern', [InternController::class, 'store']);
Route::get('/intern/{intern}', [InternController::class, 'show']);
Route::put('/intern/{intern}', [InternController::class, 'update']);
Route::delete('/intern/{intern}', [InternController::class, 'destroy']);