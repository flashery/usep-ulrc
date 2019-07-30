<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['department_id', 'name', 'description'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects(){
        return $this->hasMany(Subject::class);
    }
}
