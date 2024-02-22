<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\VerificationController;
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

//Route to do Registration
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login/google', [LoginController::class, 'loginWithGoogle'])->name('login.google');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/resetpassword', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('sendreset');
Route::post('/resetpassword', [ResetPasswordController::class, 'reset'])->name('resetpassword');
Route::get('/email/verify', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

//Route to manage Intern

Route::middleware('auth:sanctum')->group(function () {
    // Home Routes
    Route::get('/', [InternController::class, 'index']);
    Route::get('/home', [InternController::class, 'index'])->name('home');

    Route::post('/interns', [InternController::class, 'store'])->name('intern.store');
    Route::put('/interns/{id}', [InternController::class, 'update'])->name('intern.update');
    Route::delete('/interns/{id}', [InternController::class, 'destroy'])->name('intern.destroy');
    Route::get('/interns/{id}', [InternController::class, 'show'])->name('intern.show');

    // Applicant
    Route::post('/applications/{id}', [InternController::class, 'apply'])->name('apply');

    // User Profile Routes
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/user/profile/coverletter', [UserProfileController::class, 'updateCoverLetter'])->name('user.profile.coverletter');
    Route::post('/user/profile/resume', [UserProfileController::class, 'updateResume'])->name('user.profile.resume');
    Route::post('/user/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('user.profile.avatar');
});

// Company Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
});

//Favorite Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/interns/{id}', [FavoriteController::class, 'saveIntern']);
    Route::delete('/interns/{id}', [FavoriteController::class, 'unsaveIntern']);
});
