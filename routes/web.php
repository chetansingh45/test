<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Mail\RegistrationNotification;
use App\Http\Controllers\ResetPasswordController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get("auth", [AuthController::class, "index"])->name("auth.get");
Route::get("logout", [AuthController::class, "logout"])->name("logout");
Route::get("reset", [ResetPasswordController::class, "showReset"])->name("reset.show");
Route::post("reset", [ResetPasswordController::class, "reset"])->name("reset.password");
Route::post("register", [AuthController::class, "register"])->name("register.post");
Route::post("login", [AuthController::class, "login"])->name("login.post");
Route::get("dashboard", [AuthController::class, "dashboard"])->middleware('restrict')->name("get.dashboard");
Route::get("user/create", [AuthController::class, "addUser"])->name("get.adduser");
Route::get("users", [AuthController::class, "showUsers"])->name("show.users");
Route::post("changePassword", [ResetPasswordController::class, "changePassword"])->name("change.password");
Route::get("checkReset/{email}/{token}", [ResetPasswordController::class, "checkReset"]);
Route::post("user/delete", [AuthController::class, "destroy"])->name("user.delete");
Route::post("user/edit", [AuthController::class, "editUser"])->name("user.edit");
Route::post("user/update", [AuthController::class, "updateUser"])->name("user.update");

// Route::get("mail",function(){
//     Mail::to("abcde@gmail.com")->send(new RegistrationNotification);
// });


