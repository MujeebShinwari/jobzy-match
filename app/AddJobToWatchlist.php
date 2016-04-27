<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddJobToWatchlist extends Model
{
	protected $table = "seeker_watchlists";

	protected $fillable = [
		'job_id'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
