<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class EmployerCompanyCulture extends Model
	{
		protected $table = 'company_cultures';
		protected $fillable = [
			'slider_id',
			'score',
			'company_id'
		];
		protected $hidden = [
			'created_at',
			'updated_at'
		];
	}
