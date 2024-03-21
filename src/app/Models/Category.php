<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function Tasks() {
        return $this->hasMany(Task::class);
    }
    public function User(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
