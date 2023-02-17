<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;
use App\Models\UserProject;

class Project extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
