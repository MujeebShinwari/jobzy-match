<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
	protected $table = "user_employers";
	protected $fillable = [
		'company_id',
		'division_id',
		'industry_id'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
