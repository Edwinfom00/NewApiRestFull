<?php

use App\Http\Controllers\CompanyController;
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

//Route to manage Intern

Route::get('/intern', [InternController::class,'index']);
Route::post('/intern', [InternController::class, 'store']);
Route::get('/intern/{intern}', [InternController::class, 'show']);
Route::put('/intern/{intern}', [InternController::class, 'update']);
Route::delete('/intern/{intern}', [InternController::class, 'destroy']);

//Route to manage Company
Route::get('/company',[CompanyController::class, 'index']);
Route::post('/company',[CompanyController::class, 'store']);
Route::get('/company/{company}',[CompanyController::class, 'show']);
Route::put('/company/{company}',[CompanyController::class, 'update']);
Route::delete('/company/{company}',[CompanyController::class, 'delete']);