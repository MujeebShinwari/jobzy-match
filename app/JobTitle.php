<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
	protected $table = "job_titles";
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
