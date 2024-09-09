<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Task extends Model
{
    use HasFactory;


    protected $fillable = ['title', 'description', 'status', 'due_date'];

    // Set default value for status
    protected $attributes = [
        'status' => 'pending',
    ];

    // Validation rules
    public static function validate($data)
    {
        return Validator::make($data, [
            'title' => 'required|unique:tasks',
            'due_date' => 'required|date|after:today', // Due date must be in the future
        ]);
    }
}
