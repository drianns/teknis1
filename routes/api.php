<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\GeneralDataController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/master-data', [GeneralDataController::class, 'getListData']);
Route::get('/general-data', [GeneralDataController::class, 'getListData']);