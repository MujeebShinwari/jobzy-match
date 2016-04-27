<?php

	namespace App\Http\Controllers;

	use App\User;
	use Illuminate\Http\Request;

	use App\Http\Requests;

	class BaseController extends Controller
	{
		protected $user;

		public function __construct()
		{
			if (request()->has('auth_token')) {
				$user = User::whereAuthToken(request()->get('auth_token'))->first();
				if ($user) {
					$this->user = $user;
				}
			}
		}
	}
