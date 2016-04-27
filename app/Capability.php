<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
