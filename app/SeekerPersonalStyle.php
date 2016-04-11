<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeekerPersonalStyle extends Model
{
	protected $table = "seeker_personal_style";

    protected $fillable = [
	    'slider_id',
	    'score'
    ];
}
