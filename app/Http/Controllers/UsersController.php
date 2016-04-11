<?php

	namespace App\Http\Controllers;

	use App\Capability;
	use App\Certificate;
	use App\Division;
	use App\EducationLevel;
	use App\Industry;
	use App\JobTitle;
	use App\PaymentPlan;
	use App\Skill;
	use App\Slider;
	use App\SliderQuiz;
	use App\University;
	use App\User;
	use App\Company;
	use Illuminate\Http\Request;

	use App\Http\Requests;
	use Illuminate\Support\Facades\Validator;

	class UsersController extends Controller
	{
		public function store(Request $request, User $user)
		{

			//return $request->all();
			$validator = Validator::make($request->all(), [
				'first_name' => 'required|min:3',
				'last_name'  => 'required|min:3',
				'email'      => 'required|unique:users,email|email',
				'password'   => 'required',
			]);

			if ($validator->fails()) {
				return apiResponse(200, '', $validator->messages());
			}
			$inputs         = $request->all();
			$user1          = $user->create($inputs);
			$inputs['type'] = "jobseeker";
			if ($request->hasFile('resume')) {
				$inputs['resume'] = upload($request->file('resume'));
			}
			$user1->jobSeeker()->create($inputs);

			$personalStyles = json_decode($inputs['personal_styles']);
			foreach ($personalStyles as $personalStyle) {
				$user1->seekerPersonalStyle()->create([
					'slider_id' => $personalStyle->slider_id,
					'score'     => $personalStyle->score
				]);
			}
			$workStyles = json_decode($inputs['work_styles']);
			foreach ($workStyles as $workStyle) {
				$user1->seekerWorkStyle()->create([
					'slider_id' => $workStyle->slider_id,
					'score'     => $workStyle->score
				]);
			}
			if ($user1) {
				return apiResponse(200, 'Registration successful', $validator->messages());
			}

			return apiResponse(500, 'User cannot be registered', null);

		}

		public function getSignUpData($type)
		{
			$paymentPlans  = PaymentPlan::all();
			$jobTitles     = JobTitle::all();
			$sliders       = Slider::all();
			$sliderQuizzes = SliderQuiz::all();
			$companies     = Company::all();


			//Common Data for both Employer and Job Seeker
			$result['job_titles']     = $jobTitles;
			$result['payment_plans']  = $paymentPlans;
			$result['sliders']        = $sliders;
			$result['slider_quizzes'] = $sliderQuizzes;
			$result['companies']      = $companies;

			//Job seeker related data
			if ($type == 'jobSeeker') {
				$educationLevels            = EducationLevel::all();
				$educationDegrees           = EducationLevel::all();
				$universities               = University::all();
				$result['education_level']  = $educationLevels;
				$result['education_degree'] = $educationDegrees;
				$result['universities']     = $universities;
			}

			//Employer related data

			if ($type == 'employer') {
				$divisions              = Division::all();
				$industries             = Industry::all();
				$skills                 = Skill::all();
				$capabilities           = Capability::all();
				$certificates           = Certificate::all();
				$result['divisions']    = $divisions;
				$result['industries']   = $industries;
				$result['skills']       = $skills;
				$result['capabilities'] = $capabilities;
				$result['certificates'] = $certificates;
			}


			if ($result) {
				return apiResponse(200, 'OK', null, $result);
			}
			return apiResponse(404, 'sign up data not found', null, $result);;
		}

	}
