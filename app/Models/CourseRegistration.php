<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        "student_id",
        "semester",
        "session",
        "course_code",
        "course_title",
        "course_unit",
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (CourseRegistration $courseRegistration) {
            $courseRegistration->course_unit = 3;
        });
    }

}
