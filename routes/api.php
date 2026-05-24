<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get("/get/roles",[RoleController::class,"getRoles"]);
Route::post("/register",[AuthenticationController::class,"register"]);
Route::post("/resend/verification/token",[AuthenticationController::class,"resendVerificationEmail"]);
Route::post("/forget/password",[AuthenticationController::class,"forgetPassword"]);
Route::post("/reset/password",[AuthenticationController::class,"resetPassword"]);
Route::post("/login",[AuthenticationController::class,"login"]);
Route::post("/verify/account",[AuthenticationController::class,"verfiyAccount"]);
Route::post("/logout",[AuthenticationController::class,"logout"]);


Route::post("/create/company",[CompanyController::class,"createCompany"]);