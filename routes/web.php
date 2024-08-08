<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\Auth\AuthController;

Route::get('login', [AttractionController::class, 'login'])->name('login');
Route::post('post-login', [AttractionController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AttractionController::class, 'registration'])->name('register');
Route::post('post-registration', [AttractionController::class, 'postRegistration'])->name('register.post'); 
Route::post('logout', [AttractionController::class, 'logout'])->name('logout');

Route::get('/', [AttractionController::class, 'index'])->name('home');
Route::get('/comments/by-rating', [AttractionController::class, 'commentsByRating']);
Route::get('/comments/count/{id}', [AttractionController::class, 'commentCount']);
Route::get('/attractions/by-species/{speciesId}', [AttractionController::class, 'attractionsBySpecies']);
Route::get('/attractions/average-rating/{speciesId}', [AttractionController::class, 'averageRatingBySpecies']);
