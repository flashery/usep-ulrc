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
}
