<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;

Route::get('/',function(){
    return view('welcome');
});


Route::middleware('guest')->group(function(){
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
    

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected route (example)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');


Route::middleware('auth')->group(function(){

      Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
      
      Route::get('/profile',[UserController::class,'profile'])->name('profile');
      Route::get('/profile/edit',[UserController::class,'profileEditForm'])->name('profile.edit');
      Route::put('/profile/edit',[UserController::class,'updateProfile'])->name('profile.update');

      //Projects
      Route::get('/admin/projects',[ProjectController::class,'index'])->name('projects.index'); 
      Route::get('/admin/projects/{project}/detail',[ProjectController::class,'show'])->name('projects.show');
      Route::get('/admin/projects/create',[ProjectController::class,'create'])->name('projects.create');
      Route::post('/admin/projects',[ProjectController::class,'store'])->name('projects.store');
      Route::get('/admin/projects/{project}/edit',[ProjectController::class,'edit'])->name('projects.edit');
      Route::put('admin/projects/{project}',[ProjectController::class,'update'])->name('projects.update');
      Route::delete('/admin/projects/{project}/delete',[ProjectController::class,'destroy'])->name('projects.destroy');
      Route::patch('/admin/projects/{projectID}/restore',[ProjectController::class,'restore'])->name('projects.restore');
     
      //Tasks
      Route::get('/projects/{project}/tasks/{task}/show',[TaskController::class,'show'])->name('projects.task.show');
      Route::get('/projects/{project}/tasks/',[TaskController::class,'create'])->name('projects.task.create');
      Route::post('/projects/{project}/tasks/store',[TaskController::class,'store'])->name('projects.task.store');
      Route::get('/projects/{project}/tasks/{task}/edit',[TaskController::class,'edit'])->name('projects.task.edit');
      Route::put('/projects/{project}/tasks/{task}/edit',[TaskController::class,'update'])->name('projects.task.update');
      Route::delete('/projects/{project}/tasks/{task}/delete',[TaskController::class,'destroy'])->name('projects.task.destroy');
      Route::patch('/projects/{project}/tasks/{task}/restore', [TaskController::class, 'restore'])->name('projects.task.restore');
      
      //Comments
      Route::post('tasks/{task}/comment',[CommentController::class,'store'])->name('tasks.comment.store');
      Route::get('/tasks/{task}/comment/{comment}/edit',[CommentController::class,'edit'])->name('tasks.comment.edit');
      Route::put('/tasks/{task}/comment/{comment}/update', [CommentController::class, 'update'])->name('tasks.comment.update');
      Route::delete('/tasks/{task}/comment/{comment}/destroy', [CommentController::class, 'destroy'])->name('tasks.comment.destroy');
      
  
      Route::middleware('role:admin')->group(function(){

           Route::get('/admin/user_list',[UserController::class,'index'])->name('admin.user_list');
           Route::get('/admin/users/{user}/edit',[UserController::class,'edit'])->name('admin.users.edit');
           Route::put('/admin/users/{user}',[UserController::class,'update'])->name('admin.users.update');
           Route::delete('/admin/users/{user}', [UserController::class, 'destroyUser'])->name('admin.users.destroy');
           Route::patch('/admin/users/{user}/restore', [UserController::class, 'restore'])->name('admin.users.restore');
           

      });
});



