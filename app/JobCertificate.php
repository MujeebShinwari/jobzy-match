<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCertificate extends Model
{
	protected $table = "job_certificates";
	protected $fillable = [
		'id',
		'title'
	];
	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
