<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;

Route::get('/',function(){
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected route (example)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');


Route::middleware('auth')->group(function(){
      Route::get('/profile',[UserController::class,'profile'])->name('profile');
      Route::get('/profile/edit',[UserController::class,'profileEditForm'])->name('profile.edit');
      Route::put('/profile/edit',[UserController::class,'updateProfile'])->name('profile.update');

      //Projects
      Route::get('/admin/projects',[ProjectController::class,'index'])->name('projects.index'); 
      Route::get('/admin/projects/create',[ProjectController::class,'create'])->name('projects.create');
      Route::post('/admin/projects',[ProjectController::class,'store'])->name('projects.store');
      Route::get('/admin/projects/{project}/edit',[ProjectController::class,'edit'])->name('projects.edit');
      Route::put('admin/projects/{project}',[ProjectController::class,'update'])->name('projects.update');
      Route::delete('/admin/projects/{project}/delete',[ProjectController::class,'destroy'])->name('projects.destroy');

      Route::middleware('role:admin')->group(function(){

           Route::get('/admin/user_list',[UserController::class,'index'])->name('admin.user_list');
           Route::get('/admin/users/{user}/edit',[UserController::class,'edit'])->name('admin.users.edit');
           Route::put('/admin/users/{user}',[UserController::class,'update'])->name('admin.users.update');
           Route::delete('/admin/users/{user}', [UserController::class, 'destroyUser'])->name('admin.users.destroy');
           Route::patch('/admin/users/{user}/restore', [UserController::class, 'restore'])->name('admin.users.restore');
           

      });
});



