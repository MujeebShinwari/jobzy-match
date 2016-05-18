<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class JobMatch extends Model
	{
		protected $table = "job_matches";
		protected $fillable = [
			'id',
			'job_id',
			'seeker_id',
			'match_score',
			'match_date_time'
		];
		protected $hidden = [
			'created_at',
			'updated_at'
		];
	}
