<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'todolist_id',
        'name',
        'file_path'
    ];

    public function list()
    {
        return $this->belongsTo(TodoList::class, 'todolist_id');
    }
}
