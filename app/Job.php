<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class Job extends Model
	{
		protected $table = "jobs";
		protected $fillable = [
			'type',
			'title',
			'desc',
			'city',
			'state',
			'zip',
			'lat',
			'long',
			'company_id'
		];
		protected $hidden = [
			'created_at',
			'updated_at'
		];
		public function jobCapability()
		{
			return $this->hasMany('App\JobCapability', 'job_id');
		}

		public function jobCertificate()
		{
			return $this->hasMany('App\JobCertificate', 'job_id');
		}

		public function jobSkill()
		{
			return $this->hasMany('App\JobSkill', 'job_id');
		}

		public function seekerTrait()
		{
			return $this->hasMany('App\SeekerTrait', 'job_id');
		}
	}
