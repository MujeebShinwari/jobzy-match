<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeekerWorkplaceStyle extends Model
{
	protected $table = "seeker_workplace_styles";

	protected $fillable = [
		'slider_id',
		'score'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
