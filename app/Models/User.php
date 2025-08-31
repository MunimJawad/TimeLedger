<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

  // Projects this user owns
public function projects()
{
    return $this->hasMany(Project::class, 'owner_id');
}

// Projects this user is a member of
public function memberProjects()
{
    return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
}


   public function assignedTasks(){
    return $this->hasMany(Task::class,'assigned_to');
   }

   public function collaboratedTasks(){
    return $this->belongsToMany(Task::class,'task_user');
   }

   public function comments()
{
    return $this->hasMany(Comment::class);
}

    public function isAdmin(){
        return $this->role==='admin';
    }

    public function isManager(){
        return $this->role==='manager';
    }
}
