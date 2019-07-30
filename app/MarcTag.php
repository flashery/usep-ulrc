<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MarcTag extends Model
{
    protected $fillable = ['marc_tag', 'non_marc_tag', 'show_as_default'];
    protected $casts = ['show_as_default' => 'boolean'];

    public function bibs()
    {
        return $this->belongsToMany(Bib::class);
    }

}
