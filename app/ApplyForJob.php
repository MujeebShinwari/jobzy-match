<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplyForJob extends Model
{
    protected $table = "seeker_applied_jobs";

	protected $fillable = [
		'job_id',
		'cover_letter'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
