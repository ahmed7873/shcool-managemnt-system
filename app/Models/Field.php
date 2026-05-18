<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'is_required'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->withPivot('value')
            ->withTimestamps();
    }
}
