<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Term extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = ['name', 'max_lectures_per_day'];


    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classrooms_id');
    }
    public function academicyear()
    {
        return $this->belongsTo(AcademicYear::class, 'academicyear_id');
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'term_subjects');
    }
}
