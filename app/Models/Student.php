<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "last_name",
        "level",
        "matric_no",
        "department",
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function courseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function (Student $student) {
            $student->profile()->create();
        });
    }
}
