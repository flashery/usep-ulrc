<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bib extends Model
{
    public function  marc_tags()
    {
        return $this->belongsToMany(MarcTag::class)->withPivot('value');
    }

    public function  bib_marc_tags()
    {
        return $this->hasMany(BibMarcTag::class);
    }

    public function  subjects()
    {
        return $this->belongsToMany(Subject::class, 'bib_subjects');
    }

    public function  volumes()
    {
        return $this->hasMany(BibVolume::class);
    }

    public function  bibMarcTagValues()
    {
        return $this->hasMany(Bib::class);
    }
}
