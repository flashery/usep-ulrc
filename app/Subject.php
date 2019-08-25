<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['department_id', 'course_id', 'code', 'name', 'description'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function bibs()
    {
        return $this->belongsToMany(Bib::class, 'bib_subjects');
    }
}
