<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class SeekerTrait extends Model
	{
		protected $table = "seeker_traits";
		protected $fillable = [
			'slider_id',
			'score'
		];
	}
