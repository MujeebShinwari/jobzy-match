<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCapability extends Model
{
	protected $table = "job_capabilities";
	protected $fillable = [
		'id',
		'title'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
