<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BibMarcTag extends Model
{
    protected $table = 'bib_marc_tag';

    protected $fillable = ['marc_tag_id', 'value'];
}
