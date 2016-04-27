<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobShortlist extends Model
{
	protected $table='job_shortlists';
	protected $fillable = [
		'job_id',
		'seeker_id'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
