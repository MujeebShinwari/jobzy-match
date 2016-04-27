<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class JobSkill extends Model
	{
		protected $table = "job_skills";
		protected $fillable = [
			'id',
			'title'
		];
		protected $hidden = [
			'created_at',
			'updated_at'
		];
	}
