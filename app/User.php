<?php

	namespace App;

	use Illuminate\Foundation\Auth\User as Authenticatable;

	class User extends Authenticatable
	{
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'first_name',
			'last_name',
			'email',
			'password',
			'city',
			'state',
			'zip',
			'lat',
			'long',
			'phone',
			'auth_token'
		];
		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password',
			'remember_token',
			'auth_token',
			'created_at',
			'updated_at'
		];

		public function jobSeeker()
		{
			return $this->hasOne('App\JobSeeker', 'user_id');
		}

		public function employer()
		{
			return $this->hasOne('App\Employer', 'user_id');
		}

		public function seekerPersonalStyle()
		{
			return $this->hasMany('App\SeekerPersonalStyle', 'seeker_id');
		}

		public function employerJobs()
		{
			return $this->hasMany('App\Job', 'employer_id');
		}

		public function employerCompanyCulture()
		{
			return $this->hasMany('App\EmployerCompanyCulture', 'employer_id');
		}

		public function seekerWorkStyle()
		{
			return $this->hasMany('App\SeekerWorkplaceStyle', 'seeker_id');
		}

		public function applyForJob()
		{
			return $this->hasMany('App\ApplyForJob', 'seeker_id');
		}

		public function JobWatchlist()
		{
			return $this->hasMany('App\AddJobToWatchlist', 'seeker_id');
		}

		public function addJobShortlist()
		{
			return $this->hasMany('App\JobShortlist', 'seeker_id');
		}

		public function setPasswordAttribute($value)
		{
			return $this->attributes['password'] = bcrypt($value);
		}


	}
