<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bib extends Model
{
    public function  marc_tags()
    {
        return $this->belongsToMany(MarcTag::class)->withPivot('value');
    }

    public function  subjects()
    {
        return $this->belongsToMany(Subject::class, 'bib_subjects');
    }
}
