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
			'first_name', 'last_name', 'email', 'password', 'city', 'state', 'zip', 'lat', 'long', 'phone'
		];
		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password', 'remember_token',
		];

		public function jobSeeker()
		{
			return $this->hasOne('App\JobSeeker');
		}

		public function seekerPersonalStyle()
		{
			return $this->hasMany('App\SeekerPersonalStyle', 'seeker_id');
		}

		public function seekerWorkStyle()
		{
			return $this->hasMany('App\SeekerWorkStyle', 'seeker_id');
		}
	}
