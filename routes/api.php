<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'categories','as'=>'categories.'],function (){
    Route::get('/',[CategoryController::class,'index'])->name('index');
    Route::post('/',[CategoryController::class,'create'])->name('create');
});

Route::group(['prefix'=>'websites','as'=>'websites.'],function (){
   Route::get('/',[WebsiteController::class,'index'])->name('index');
});

Route::group(['prefix'=>'users','as'=>'users.'],function (){
    Route::post('/login',[UserController::class,'login'])->name('login');

    Route::group(['middleware'=>'auth:sanctum'],function (){
       Route::post('/logout',[UserController::class,'logout'])->name('logout');
       Route::get('/favorites',[UserController::class,'favorite_websites'])->name('favorite_websites');
       Route::post('/favorites',[UserController::class,'add_to_favorite'])->name('add_to_favorite');
       Route::delete('/favorites',[UserController::class,'remove_from_favorite'])->name('remove_from_favorite');
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
