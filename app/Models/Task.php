<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=['project_id','assigned_to','title','description','status'];
    
    //
    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function assignee(){
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function collaborators(){
        return $this->belongsToMany(User::class,'task_user');
    }

}
