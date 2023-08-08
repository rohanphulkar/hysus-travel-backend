<?php

use App\Http\Controllers\AdImageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
// Admin Controller Routes
Route::group([
    'middleware'=>['return-json'],
    'prefix'=>'admin'
],function(){
    Route::post("/register",[AdminController::class,'register']);
    Route::post("/login",[AdminController::class,'login']);
    Route::post('/forgot-password',[AdminController::class,'forgotPassword']);
    Route::post('/reset-password/{token}',[AdminController::class,'resetPassword']);
    Route::group(['middleware' => ['auth:admin-api']], function () {
        Route::get("/profile",[AdminController::class,'profile']);
        Route::get("/logout",[AdminController::class,'logout']);
    });
});

// User Controller Routes
Route::group([
    'middleware'=>['return-json'],
    'prefix'=>'user'
],function(){
    Route::post("/register",[UserController::class,'register']);
    Route::post("/login",[UserController::class,'login']);
    Route::post('/forgot-password',[UserController::class,'forgotPassword']);
    Route::post('/reset-password/{token}',[UserController::class,'resetPassword']);
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get("/profile",[UserController::class,'profile']);
        Route::get("/logout",[UserController::class,'logout']);
    });
});


Route::group(['middleware'=>'return-json','prefix'=>'packages'],function(){
    Route::get("/",[PackageController::class,'index']);
    Route::get("/{id}",[PackageController::class,'show']);
    Route::group(['middleware' => ['auth:admin-api']], function () {
        Route::post("/",[PackageController::class,'store']);
        Route::put("/{id}",[PackageController::class,'update']);
        Route::delete("/{id}",[PackageController::class,'destroy']);
    });
});

Route::group(['middleware'=>'return-json','prefix'=>'itineraries'],function(){
    Route::get("/",[ItineraryController::class,'index']);
    Route::get("/{id}",[PackageController::class,'getItineraryById']);
    Route::get("/booking/{id}",[PackageController::class,'getItineraryByBookingId']);
    Route::group(['middleware' => ['auth:admin-api']], function () {
        Route::delete("/{id}",[PackageController::class,'destroy']);
    });
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get("/user",[PackageController::class,'getItineraryByUser']);
    });
});
Route::group(['middleware'=>'return-json','prefix'=>'bookings'],function(){
    
    Route::get("/{id}",[BookingController::class,'getBookingById']);
    Route::group(['middleware' => ['auth:admin-api']], function () {
        Route::get("/",[BookingController::class,'index']);
        Route::get("/package/{id}",[BookingController::class,'getBookingsByPackage']);
    });
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get("/user",[BookingController::class,'getBookingsOfUser']);
        Route::get("/cancel/{id}",[BookingController::class,'cancelBooking']);
    });
});


