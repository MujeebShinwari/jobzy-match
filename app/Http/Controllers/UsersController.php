<?php

	namespace App\Http\Controllers;

	use App\ApplyForJob;
	use App\Capability;
	use App\Certificate;
	use App\ContactUs;
	use App\Division;
	use App\EducationLevel;
	use App\Employer;
	use App\EmployerCompanyCulture;
	use App\Industry;
	use App\JobCapability;
	use App\JobCertificate;
	use App\JobSeeker;
	use App\JobShortlist;
	use App\JobSkill;
	use App\JobTitle;
	use App\PaymentPlan;
	use App\Quote;
	use App\SeekerPersonalStyle;
	use App\SeekerTrait;
	use App\Skill;
	use App\Slider;
	use App\SliderQuiz;
	use App\University;
	use App\User;
	use App\Job;
	use App\Company;
	use DB;
	use Exception;
	use Illuminate\Http\Request;
	use App\Http\Controllers;

	use App\Http\Requests;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Mail;
	use Illuminate\Support\Facades\Validator;

	class UsersController extends BaseController
	{
		public function signUp(Request $request, User $user)
		{

			$inputs = $request->all();
			try {
				//return $request->all();
				$validator = Validator::make($inputs, [
					'first_name' => 'required|min:3',
					'last_name'  => 'required|min:3',
					'email'      => 'required|unique:users,email|email',
					'password'   => 'required',
					'type'       => 'required',
				]);

				if ($validator->fails()) {
					return apiResponse(401, 'Please provide valid inputs', $validator->messages());
				}

				$parentUser = $user->create($inputs);


				//Job seeker sign up

				if ($inputs['type'] == 'seeker') {

					//upload job seeker resume
					if ($request->hasFile('resume')) {
						$inputs['resume'] = upload($request->file('resume'));
					}

					$childUser = $parentUser->jobSeeker()->create($inputs);

					$personalStyles = json_decode($inputs['personal_styles']);
					foreach ($personalStyles as $personalStyle) {
						$parentUser->seekerPersonalStyle()->create([
							'slider_id' => $personalStyle->slider_id,
							'score'     => $personalStyle->score
						]);
					}
					$workStyles = json_decode($inputs['work_styles']);
					foreach ($workStyles as $workStyle) {
						$parentUser->seekerWorkStyle()->create([
							'slider_id' => $workStyle->slider_id,
							'score'     => $workStyle->score
						]);
					}

					$parentUser = [
						'personal_styles' => $personalStyles,
						'work_styles'     => $workStyles
					];
				}
				if ($inputs['type'] == 'employer') {
					$inputs['name'] = $inputs['company_name'];
					$inputs['desc'] = $inputs['company_desc'];
					$company        = Company::whereName($inputs['name'])->first();

					if (!$company) {
						//upload company logo
						if ($request->hasFile('company_logo')) {
							$inputs['logo'] = upload($request->file('company_logo'));
						}
						$company = Company::create($inputs);
					}

					$inputs['company_id'] = $company->id;

					$childUser = $parentUser->employer()->create($inputs);

					$companyCultures = json_decode($inputs['company_cultures']);
					foreach ($companyCultures as $companyCulture) {
						$parentUser->employerCompanyCulture()->create([
							'slider_id'  => $companyCulture->slider_id,
							'score'      => $companyCulture->score,
							'company_id' => $childUser->company_id
						]);
					}
				}

				if ($parentUser) {
					//Login after successful registration
					return $this->login();
				}

				return apiResponse(500, 'User cannot be registered', null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}

		}

		public function postJob(Request $request)
		{
			$inputs = $request->all();
			try {
				$validator = Validator::make($inputs, [
					'title' => 'required',
					'type'  => 'required',
					'city'  => 'required',
					'state' => 'required',
					'zip'   => 'required',
				]);

				if ($validator->fails()) {
					return apiResponse(401, 'unknown error occurred', $validator->messages());
				}

				//Check if company not exist than insert it
				$company = Company::whereName($inputs['company_name'])->first();

				if (!$company) {
					$companyData = [
						'name' => $inputs['company_name']
					];
					$company     = Company::create($companyData);
				}
				$inputs['company_id'] = $company->id;
				//create job
				$jobCreated = $this->user->employerJobs()->create($inputs);

				$seekerTraits = json_decode($inputs['seeker_traits']);
				foreach ($seekerTraits as $seekerTrait) {
					$jobCreated->seekerTrait()->create([
						'slider_id' => $seekerTrait->slider_id,
						'score'     => $seekerTrait->score
					]);
				}

				$certifications = json_decode($inputs['certifications']);
				foreach ($certifications as $certificate) {
					$jobCertifications = $jobCreated->jobCertificate()->create([
						'title' => $certificate->title
					]);
				}
				$skills = json_decode($inputs['skills']);
				foreach ($skills as $skill) {
					$jobSkills = $jobCreated->jobSkill()->create([
						'title' => $skill->title
					]);
				}
				$capabilities = json_decode($inputs['capabilities']);
				foreach ($capabilities as $capability) {
					$jobCapabilities = $jobCreated->jobCapability()->create([
						'title' => $capability->title
					]);
				}

				if ($jobCreated) {
					if ($inputs['type'] == 1) {
						$message = 'Job created successfully';
					} else if ($inputs['type'] == 0) {
						$message = 'Screen created successfully';
					}
					$data = [
						'job_id'         => $jobCreated['id'],
						'certifications' => $jobCertifications,
						'skills'         => $jobSkills,
						'capabilities'   => $jobCapabilities
					];
					return apiResponse(200, $message, null, $data);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function updateJob(Request $request)
		{
			$inputs = $request->all();
			try {
				$validator = Validator::make($inputs, [
					'job_id' => 'required',
					'type'   => 'required',
					'title'  => 'required',
					'city'   => 'required',
					'state'  => 'required',
					'zip'    => 'required',
				]);

				if ($validator->fails()) {
					return apiResponse(401, 'unknown error occurred', $validator->messages());
				}
				//update job
				$jobUpdated = $this->user->employerJobs()->find($inputs['job_id'])->update($inputs);


				if ($jobUpdated) {
					if ($inputs['type'] == 1) {
						$message = 'Job updated successfully';
					} else if ($inputs['type'] == 0) {
						$message = 'Screen updated successfully';
					}
					return apiResponse(200, $message, null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function getJobDetail(Request $request)
		{
			$inputs = $request->all();
			try {
				$validator = Validator::make($inputs, [
					'job_id' => 'required'
				]);

				if ($validator->fails()) {
					return apiResponse(401, 'Please provide correct parameters', $validator->messages());
				}

				$job = Job::find($inputs['job_id'])->first();
				//

				$jobSkills       = $job->jobSkill()->get();
				$jobCapabilities = $job->jobCapability()->get();
				$jobCertificates = $job->jobCertificate()->get();

				$job->skills       = $jobSkills;
				$job->capabilities = $jobCapabilities;
				$job->certificates = $jobCertificates;

				//unset these values
				array_pull($job, 'is_active');
				array_pull($job, 'company_quality_rank');
				array_pull($job, 'candidate_quality_rank');
				array_pull($job, 'employer_id');


				return apiResponse(200, 'OK', null, $job);

				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function getEmployerJobAndScreens()
		{

			$inputs = request()->all();
			try {
				$data = DB::select("SELECT j.`id` AS job_id , j.`title` AS job_title,j.`desc`,j.`zip`,j.`state`,j.`city`,
				j.`type` AS job_type,j.`lat`,j.`long`,jp.`duration` AS payment_duration,
						DATEDIFF(jp.`expiry_date`,CURRENT_DATE() )AS payment_expiry_days_left,
						(SELECT COUNT(*) FROM seeker_applied_jobs ap WHERE ap.`job_id`=j.`id`) AS applicants_count,
						(SELECT COUNT(*) FROM seeker_applied_jobs ap WHERE ap.`job_id`=j.`id` AND ap.`is_shortlisted`=1) AS
						shortlist_count, 0 as matches_count
                        FROM jobs j
						LEFT JOIN job_payments jp ON j.`id`=jp.`job_id`
						WHERE j.`employer_id` ='" . $this->user->id . "' AND j.`is_active`=1");
				if ($data) {
					$message = 'OK';

					return apiResponse(200, $message, null, $data);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		function calculateSeekerJobMatchesDistance($lat, $long)
		{
			//Seeker Lat Long is
			//$lat , $long
			return " ROUND(1000 * 6371 * 2 * atan(SQRT(SIN(RADIANS(j.`lat` - $lat)/2) * SIN(RADIANS(j.`lat` - $lat)/2) + COS(RADIANS(j
			.`lat`)) * COS(RADIANS($lat)) * SIN(RADIANS(j.`long` - $long)/2) * SIN(RADIANS(j.`long` - $long)/2)),
			SQRT(1-SIN(RADIANS(j.`lat` - $lat)/2) * SIN(RADIANS(j.`lat` - $lat)/2) + COS(RADIANS(j.`lat`)) * COS(RADIANS($lat)) * SIN
			(RADIANS(j.`long` - $long)/2) * SIN(RADIANS(j.`long` - $long)/2))),
	0) as distance ";
		}

		public function getJobSeekerMatches()
		{

//SELECT CASE WHEN 'software developer' LIKE 'software dveloper' THEN 5  WHEN 'software developer' LIKE '%software%' THEN 3  WHEN 'software developer' LIKE '%developer%' THEN 3 ELSE 0 END AS result
			//query

			/*SELECT
(result.company_quality_rank +
result.personalStyleScore +
result.workStyleScore +
result.minimumEducationScore +
result.jobPropertiesScore) AS totalScore,result.* FROM

(SELECT  CASE WHEN (SELECT COUNT(*) FROM seeker_applied_jobs ap WHERE ap.`job_id`=j.`id` AND ap.`seeker_id`=2) AS isApplied > 0 THEN 1 ELSE 0 END,j.`id` AS jobId,j.`title` AS jobTitle,j.`desc` AS jobDescription,j.`company_quality_rank`,j.`lat`,j.`long`,
j.`city`,j.`state`,j.`zip`,
CASE WHEN j.`title` LIKE 'software' THEN 5 ELSE 0 END AS titleScore,
ROUND(SUM(5-(2*(ABS(st.`score`-sps.`score`))))/COUNT(st.`slider_id`)) AS personalStyleScore,
ROUND(SUM(5-(2*(ABS(cc.`score`-sws.`score`))))/COUNT(st.`slider_id`)) AS workStyleScore,
ROUND(1000 * 6371 * 2 * ATAN(SQRT(SIN(RADIANS(j.`lat` - 31.4004)/2) * SIN(RADIANS(j.`lat` - 31.4004)/2) + COS(RADIANS(j
.`lat`)) * COS(RADIANS(31.4004)) * SIN(RADIANS(j.`long` - 74.1689)/2) * SIN(RADIANS(j.`long` - 74.1689)/2)),
SQRT(1-SIN(RADIANS(j.`lat` - 31.4004)/2) * SIN(RADIANS(j.`lat` - 31.4004)/2) + COS(RADIANS(j.`lat`)) * COS(RADIANS(31.4004)) * SIN
(RADIANS(j.`long` - 74.1689)/2) * SIN(RADIANS(j.`long` - 74.1689)/2))),
0) AS distance , 0 AS minimumEducationScore,0 AS jobPropertiesScore
FROM jobs j
JOIN seeker_traits st ON j.`id`=st.`job_id`
JOIN seeker_personal_styles sps ON st.`slider_id` = sps.`slider_id`
JOIN company_cultures cc ON j.`company_id`=cc.`company_id` AND cc.`employer_id` = j.`employer_id`
JOIN seeker_workplace_styles sws ON cc.`slider_id`=sws.`slider_id`
GROUP BY j.`id`
HAVING distance < 80450 ) AS result  */
			$inputs = request()->all();

			//explode by space
			$titleArray      = explode(' ', $inputs['target_job_title']);
			$titleMatchQuery = " CASE WHEN j.`title` LIKE '" . $inputs['target_job_title'] . "' THEN 5 ";
			if (count($titleArray) > 1) {
				foreach ($titleArray as $t) {
					$titleMatchQuery .= " WHEN j.`title` LIKE '%" . $t . "%' THEN 3";
				}
			}
			$titleMatchQuery .= " ELSE 0 END AS title_score";
			try {
				$calcDistance = $this->calculateSeekerJobMatchesDistance($this->user->lat, $this->user->long);

				$data = DB::select("SELECT
 							(result.company_quality_rank +
 							result.personal_style_score +
 							result.work_style_score +
 							result.minimum_education_score +
 							result.job_properties_score) AS total_score,result.* FROM

 				(SELECT
 				CASE WHEN (SELECT COUNT(*) FROM seeker_applied_jobs ap WHERE ap.`job_id`=j.`id` AND ap.`seeker_id`='" . $this->user->id . "')
 				  > 0
 				THEN 1 ELSE 0 END AS is_applied,
CASE WHEN (SELECT COUNT(*) FROM seeker_watchlists sw WHERE sw.`job_id`=j.`id` AND sw.`seeker_id`='" . $this->user->id . "')  > 0 THEN 1
ELSE 0 END AS is_watched,
 				j.`id` as job_id,j.`title` as job_title,j.`desc` as job_description,j.`company_quality_rank`,j.`lat`,j.`long`,
 				j.`city`,j.`state`,j.`zip`,
 				$titleMatchQuery,
				ROUND(SUM(5-(2*(ABS(st.`score`-sps.`score`))))/COUNT(st.`slider_id`)) AS personal_style_score,
				ROUND(SUM(5-(2*(ABS(cc.`score`-sws.`score`))))/COUNT(st.`slider_id`)) AS work_style_score,
				$calcDistance  , 0 AS minimum_education_score,0 AS job_properties_score
                FROM jobs j
			    JOIN seeker_traits st ON j.`id`=st.`job_id`
                JOIN seeker_personal_styles sps ON st.`slider_id` = sps.`slider_id`
                JOIN company_cultures cc ON j.`company_id`=cc.`company_id` AND cc.`employer_id` = j.`employer_id`
                JOIN seeker_workplace_styles sws ON cc.`slider_id`=sws.`slider_id`
                GROUP BY j.`id`
                HAVING distance < 80450 ) AS result");
				/*			return DB::select("SELECT j.`title`,j.`lat`,j.`long`, $calcDistance from jobs j");
							return Job::where('title', 'LIKE', '%' . $inputs['target_job_title'] . '%')->get();
							$user = $this->user;

							$userSeeker = $this->user->jobSeeker()->whereUserId($this->user->id)->first();
							$data       = array_merge($user->toArray(), $userSeeker->toArray());*/
				$random_quote = Quote::orderBy(DB::raw('RAND()'))->take(1)->get();
				if ($data) {
					$extraDataKey = 'quote';
//					return $data;
					$message = 'OK';
					return apiResponse(200, $message, null, $data, $extraDataKey, $random_quote);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function updateEmployerCompanyInfo(Request $request)
		{
			$inputs = $request->all();
			try {
				$validator = Validator::make($inputs, [
					'company_name' => 'required'

				]);

				if ($validator->fails()) {
					return apiResponse(401, 'unknown error occurred', $validator->messages());
				}

				$inputs['name'] = $inputs['company_name'];
				$inputs['desc'] = $inputs['company_desc'];
				$company        = Company::whereName($inputs['name'])->first();

				//upload company logo
				if ($request->hasFile('company_logo')) {
					$inputs['logo'] = upload($request->file('company_logo'));
				}
				if (!$company) {
					$company = Company::create($inputs);
				} else {
					Company::find($company->id)->update($inputs);
				}
				$inputs['company_id'] = $company->id;

				$emplyerCompanyData = [
					'company_id'  => $company->id,
					'division_id' => $inputs['division_id']
				];
				//update Employer company
				$employerCompanyUpdated = $this->user->employer()->update($emplyerCompanyData);

				if ($employerCompanyUpdated) {
					$message = 'Employer company info updated successfully';

					return apiResponse(200, $message, null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function updatePersonalStyle()
		{

			$inputs = request()->all();
			try {
				$updateUserPersonalStyles = json_decode($inputs['personal_styles']);
				foreach ($updateUserPersonalStyles as $updateUserPersonalStyle) {
					$this->user->seekerPersonalStyle()->whereSliderId($updateUserPersonalStyle->slider_id)->update([
						'score' => $updateUserPersonalStyle->score
					]);
				}
				if ($updateUserPersonalStyle) {
					return apiResponse(200, 'personal style updated successfully', null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function updateSeekerTraits()
		{
			$inputs = request()->all();
			try {
				$traits = json_decode($inputs['seeker_traits']);

				foreach ($traits as $trait) {
					$seekerTrait = SeekerTrait::where('slider_id', $trait->slider_id)
						->where('job_id', $inputs['job_id'])->first();
					$traitUpdate = $seekerTrait->update([
						'score' => $trait->score
					]);
				}
				if ($traitUpdate) {
					return apiResponse(200, 'seeker traits updated successfully', null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function updateCompanyCulture()
		{
			$inputs = request()->all();

			$companyCultures = json_decode($inputs['company_cultures']);

			foreach ($companyCultures as $c) {

				$cultureUpdate = $this->user->employerCompanyCulture()
					->where('company_id', $inputs['company_id'])
					->where('slider_id', $c->slider_id)
					->update([
						'score' => $c->score
					]);

				/*$culture       = EmployerCompanyCulture::where('slider_id', $c->slider_id)
					->where('company_id', $inputs['company_id'])
					->where('employer_id', $this->user->id)
					->first();
				$cultureUpdate = $culture->update([
					'score' => $c->score
				]);*/
			}


			//try {
			if ($cultureUpdate) {
				return apiResponse(200, 'company culture updated successfully', null, null);
			}
			return apiResponse(500, 'error occurred', null, null);
			/*} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}*/
		}

		public function updateJobInfo()
		{
			$inputs = request()->all();

			try {
				$message        = 'Job ';
				$certifications = json_decode($inputs['certifications']);
				foreach ($certifications as $certificate) {
					$updateJobCertificate = JobCertificate::find($certificate->id)->update([
						'title' => $certificate->title
					]);

				}
				if ($updateJobCertificate) {
					$message .= ' certificates';
				}
				$skills = json_decode($inputs['skills']);
				foreach ($skills as $skill) {
					$updateJobSkill = JobSkill::find($skill->id)->update([
						'title' => $skill->title
					]);

				}
				if ($updateJobSkill) {
					$message .= ', skills';
				}
				$capabilities = json_decode($inputs['capabilities']);
				foreach ($capabilities as $capability) {
					$updateJobCapability = JobCapability::find($capability->id)->update([
						'title' => $capability->title
					]);
				}
				if ($updateJobCapability) {
					$message .= (($message != '') ? ' and' : ', ') . ' capabilities';
				}
				if ($updateJobCertificate || $updateJobSkill || $updateJobCapability) {
					$message .= ' updated successfully ';
					return apiResponse(200, $message, null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}

		}

		public function updateWorkStyle()
		{

			$inputs = request()->all();
			try {
				$updateUserWorkStyles = json_decode($inputs['work_styles']);
				foreach ($updateUserWorkStyles as $updateUserWorkStyle) {
					$this->user->seekerWorkStyle()->whereSliderId($updateUserWorkStyle->slider_id)->update([
						'score' => $updateUserWorkStyle->score
					]);
				}
				if ($updateUserWorkStyle) {
					return apiResponse(200, 'Job seeker work style updated successfully', null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function login()
		{
			$inputs = request()->all();
			try {
				$loginCredentials = [
					'email'    => $inputs['email'],
					'password' => $inputs['password']
				];
				if (Auth::attempt($loginCredentials)) {
					$user = auth()->user();
					$user->fill([
						'auth_token' => bcrypt($user->email)
					])->save();
					$data = [
						'user_id'    => $user->id,
						'auth_token' => $user->auth_token
					];
					return apiResponse(200, 'Login successful', null, $data);
				}
				return apiResponse(401, 'email or password is incorrect', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function logout()
		{
			try {
				$user = User::whereAuthToken(request()->get('auth_token'))->first();
				if ($user) {
					$user->fill([
						'auth_token' => ''
					])->save();
					return apiResponse(200, 'Logout successful', null, null);
				}
				return apiResponse(401, 'unknown error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function getBasicData($type)
		{
			try {
				$paymentPlans = PaymentPlan::all();
				$jobTitles    = JobTitle::all();
				$sliders      = Slider::with('sliderQuiz')->get();
				$companies    = Company::all();

				//Common Data for both Employer and Job Seeker
				$result['job_titles']    = $jobTitles;
				$result['payment_plans'] = $paymentPlans;
				$result['companies']     = $companies;

				$result['slider'] = $sliders;
				//Job seeker related data
				if ($type == 'seeker') {
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
				return apiResponse(404, 'sign up data not found', null, $result);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function applyForJob()
		{
			$message = '';
			$inputs  = request()->all();
			try {
				$user  = $this->user;
				$check = $user->applyForJob()->where('job_id', $inputs['job_id'])->first();
				// flag =>
				// 1 => Apply for job
				// 0 => Delete already applied job
				if ($inputs['flag'] == 1) {
					if (!$check) {
						$user->applyForJob()->create([
							'job_id'       => $inputs['job_id'],
							'cover_letter' => $inputs['cover_letter']
						]);
						$message = 'You have successfully applied for this job';
					} else {
						$message = 'You have already applied for this job';
					}
				} else if ($inputs['flag'] == 0) {
					if ($check) {
						$user    = $user->applyForJob()->where('job_id', $inputs['job_id'])->delete();
						$message = 'Apply canceled successfully';
					} else {
						$message = 'You have not applied for this job yet';
					}
				}
				if ($user) {
					return apiResponse(200, $message, null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function myAppliedJobsList()
		{

			$inputs = request()->all();
			try {
				$user   = $this->user;
				$result = $user->applyForJob()->get();
				if ($result) {
					foreach ($result as $r) {
						$data[] = [
							'job_id'       => $r->job_id,
							'cover_letter' => $r->cover_letter
						];
					}
					$message = 'OK';
					return apiResponse(200, $message, null, $data);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function myWatchlistedJobsList()
		{

			$inputs = request()->all();
			try {
				$user   = $this->user;
				$result = $user->JobWatchlist()->get();

				if (count($result) > 0) {
					foreach ($result as $r) {
						$data[] = [
							'job_id' => $r->job_id
						];
					}
					$message = 'OK';
					return apiResponse(200, $message, null, $data);
				} else {
					return apiResponse(404, 'watchlist empty', null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function updateUserInfo()
		{

			$inputs = request()->all();
			try {
				$user       = $this->user;
				$updateUser = $user->update([
					'first_name' => $inputs['first_name'],
					'last_name'  => $inputs['last_name']
				]);
				if ($updateUser) {
					return apiResponse(200, 'Profile updated successfully', null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function updateJobSeekerExperience()
		{

			$inputs = request()->all();
			try {
				$user       = $this->user;
				$updateUser = JobSeeker::find($user->id)->update([
					'target_job_title'               => $inputs['target_job_title'],
					'recent_company'                 => $inputs['recent_company'],
					'education_level_id'             => $inputs['education_level_id'],
					'education_degree_id'            => $inputs['education_degree_id'],
					'recent_institution_attended_id' => $inputs['recent_institution_attended_id'],
					'is_block_recent_company_id'     => $inputs['is_block_recent_company_id']
				]);
				if ($updateUser) {
					return apiResponse(200, 'Experience updated successfully', null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function addJobToWatchlist()
		{
			$message = '';
			try {
				$inputs = request()->all();
				$user   = $this->user;
				$check  = $user->JobWatchlist()->where('job_id', $inputs['job_id'])->first();
				// flag =>
				// 1 => add job to watchlist
				// 0 => Delete already watchlisted job
				if ($inputs['flag'] == 1) {
					if (!$check) {
						$user->JobWatchlist()->create([
							'job_id' => $inputs['job_id']
						]);
						$message = 'job added to watchlist';
					} else {
						$message = 'Already in watchlist';
					}
				} else if ($inputs['flag'] == 0) {
					if ($check) {
						$user    = $user->JobWatchlist()->where('job_id', $inputs['job_id'])->delete();
						$message = 'job removed from watchlist';
					} else {
						$message = 'Job not in watchlist yet';
					}
				}

				if ($user) {
					return apiResponse(200, $message, null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}

		public function contactUs()
		{
			$inputs = request()->all();
			//try {
			$inputs = request()->all();
			$flag   = ContactUs::create($inputs);
			if ($flag) {

				Mail::raw($inputs['message'],function ($message) use ($inputs) {
					$message->from($inputs['email'], $inputs['name']);
					$message->to('mujeeb@suavesolutions.net')->subject('Jobzy Support');

				});
				if( count( Mail::failures() ) > 0 ) {

				} else {
					return apiResponse(200, 'Thank you for contacting us , we will get back to you ASAP', null, null);
				}

				/*				Mail::send('emails.contact', ['key' => 'value'], function($message)
								{
									$message->to('mujeeb@suavesolutions.net', 'Jozby Support')->subject('Contact Us Query');
								});*/

			}
			return apiResponse(500, 'error occurred', null, null);
			/*} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}*/
		}

		public function addJobShortlist()
		{
			$message = '';
			try {
				$inputs = request()->all();
				$user   = $this->user;
//				$check  = $user->addJobShortlist()->where('job_id', $inputs['job_id'])->first();
				$check = JobShortlist::where('job_id', $inputs['job_id'])
					->where('seeker_id', $inputs['user_id'])
					->first();
				// flag =>
				// 1 => add job to watchlist
				// 0 => Delete already watchlisted job
				if ($inputs['flag'] == 1) {

					if (!$check) {
						JobShortlist::create([
							'job_id'    => $inputs['job_id'],
							'seeker_id' => $inputs['user_id']
						]);
						$message = 'User added to shortlist';
					} else {
						$message = 'Already shortlisted';
					}


				} else if ($inputs['flag'] == 0) {
					if ($check) {
						$user    = JobShortlist::where('job_id', $inputs['job_id'])
							->where('seeker_id', $inputs['user_id'])
							->delete();
						$message = 'User removed from shortlist';
					} else {
						$message = 'User not shortlisted yet';
					}

				}

				if ($user) {
					return apiResponse(200, $message, null, null);
				}
				return apiResponse(500, 'error occurred', null, null);
			} catch (Exception $ex) {
				return apiResponse(403, 'unknown error occurred', null, null);
			}
		}


	}
