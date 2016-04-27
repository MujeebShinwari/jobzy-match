<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeekerPersonalStyle extends Model
{
	protected $table = "seeker_personal_styles";

    protected $fillable = [
	    'slider_id',
	    'score'
    ];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
