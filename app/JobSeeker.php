<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class JobSeeker extends Model
	{
		protected $table = "user_job_seekers";
		protected $fillable = [
			'target_job_title',
			'target_job_location',
			'recent_job_title_id',
			'resume',
			'recent_company',
			'target_salary',
			'education_level_id',
			'education_id',
			'recent_institution_attended_id',
			'is_block_recent_company_id'
		];
	}
