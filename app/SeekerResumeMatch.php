<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class SeekerResumeMatch extends Model
	{
		protected $table = "seeker_resume_matches";
		protected $fillable = [
			'seeker_id',
			'skill_match_count',
			'capability_match_count',
			'certificate_match_count',
			'leadership_match_count',
			'institute_match_count',
			'company_match_count',
			'extracurricular_match_count',
		];
	}
