<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $table = 'companies';
	protected $fillable = [
		'name',
		'desc',
		'logo'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
