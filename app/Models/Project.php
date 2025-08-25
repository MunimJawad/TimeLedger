<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable=['name','description','owner_id'];

   // Owner of the project
public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}

// Members of the project
public function members()
{
    return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
}
}
