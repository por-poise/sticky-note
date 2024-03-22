<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Category;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'due_date',
        'color',
        'status',
    ];

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function Category() {
        return $this->belongsTo(Category::class);
    }
}
