<?php

namespace App;

use App\Course;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'image', 'description'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function subjects()
    {
        return $this->hasManyThrough(Subject::class,Course::class);
    }
}
