<?php

	Route::group(['prefix' => 'api/v1', 'middleware' => ['web']], function () {

		//Sign up API
		// api/v1/users/create
		Route::post('users/create', 'UsersController@store');

		//Get Sign UP data
		// api/v1/users/getSignUpData/type

		Route::get('users/getSignUpData/{type}', 'UsersController@getSignUpData');
		// Route having authentic request should be placed inside this group
		Route::group(['middleware' => 'verifyToken'], function () {

			// API Url

		});

	});