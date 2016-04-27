<?php

	Route::group(['prefix' => 'api/v1', 'middleware' => ['web']], function () {


		//user signup
		Route::post('users/signUp', 'UsersController@signUp');

		Route::get('users/getBasicData/{type}', 'UsersController@getBasicData');

		Route::get('users/login', 'UsersController@login');


		// Route having authentic request should be placed inside this group
		Route::group(['middleware' => 'verifyToken'], function () {
			Route::get('search/getJobSeekerMatches', 'UsersController@getJobSeekerMatches');

			Route::get('users/logout', 'UsersController@logout');

			Route::get('users/updateUserInfo', 'UsersController@updateUserInfo');

			Route::get('users/updateJobSeekerExperience', 'UsersController@updateJobSeekerExperience');

			Route::get('users/updatePersonalStyle', 'UsersController@updatePersonalStyle');

			Route::get('users/updateWorkStyle', 'UsersController@updateWorkStyle');

			Route::post('users/updateEmployerCompanyInfo', 'UsersController@updateEmployerCompanyInfo');

			Route::get('users/updateCompanyCulture', 'UsersController@updateCompanyCulture');

			Route::get('users/getEmployerData', 'UsersController@getEmployerData');

			Route::get('users/myAppliedJobsList', 'UsersController@myAppliedJobsList');

			Route::get('users/myWatchlistedJobsList', 'UsersController@myWatchlistedJobsList');

			Route::get('users/contact', 'UsersController@contactUs');

			Route::get('search/getEmployerJobAndScreens', 'UsersController@getEmployerJobAndScreens');

			Route::post('jobs/postJob', 'UsersController@postJob');

			Route::get('jobs/updateJob', 'UsersController@updateJob');

			Route::get('jobs/updateSeekerTraits', 'UsersController@updateSeekerTraits');

			Route::get('jobs/updateJobInfo', 'UsersController@updateJobInfo');

			Route::get('jobs/jobApply', 'UsersController@applyForJob');

			Route::get('jobs/jobWatchlist', 'UsersController@addJobToWatchlist');

			Route::get('jobs/jobShortlist', 'UsersController@addJobShortlist');

			Route::get('jobs/getJobDetail', 'UsersController@getJobDetail');

		});

	});

