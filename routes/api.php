<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken($request->device_name)->plainTextToken;
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/especies', [ApiController::class, 'listarEspecies']);
    Route::post('/guardar-comentario', [ApiController::class, 'create']);
    Route::get('/especies/{id}', [ApiController::class, 'obtenerEspecie']);
    Route::put('/update/{id}', [ApiController::class, 'update']);
});
