<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeekerWorkStyle extends Model
{
	protected $table = "seeker_work_style";

	protected $fillable = [
		'slider_id',
		'score'
	];
}
