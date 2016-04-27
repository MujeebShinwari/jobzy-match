<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
	protected $hidden = [
		'created_at',
		'updated_at'
	];

	public function sliderQuiz()
	{
		return $this->hasMany('App\SliderQuiz', 'slider_id');
	}
}
