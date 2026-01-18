<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AcademicYear extends Model
{
    use HasTranslations;
    public $translatable = ['academicyear'];
    protected $fillable = ['academicyear'];

    public function terms()
    {
        return $this->hasMany(Term::class, 'academicyear_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'academicyear_id');
    }
}
