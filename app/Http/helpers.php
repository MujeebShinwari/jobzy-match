<?php
	/**
	 * Created by PhpStorm.
	 * User: Mujeeb
	 * Date: 4/8/16
	 * Time: 5:05 PM
	 */
	/**
	 * @param $statusCode
	 * @param null $message
	 * @param null $errors
	 * @param null $data
	 * @return mixed
	 */
	function apiResponse($statusCode, $message = null, $errors = null, $data = null, $extraDataKey = null, $extraData = null)
	{
		$result = [
			'status_code' => $statusCode,
			'message'     => $message
		];
		if ($errors != null) {
			$result['errors'] = $errors;
		}
		if ($data != null) {
			$result['result'] = $data;
			if (!is_null($extraData)) {
				$result[$extraDataKey] = $extraData;
			}
		}
		return response()->json($result);
	}

	function upload($file)
	{
		$fileName = rand() . uniqid() . '.' . $file->getClientOriginalExtension();
		$dir      = public_path() . '/uploads';
		if (!@file_exists($dir)) {
			mkdir($dir, 777, TRUE);
		}

		if($file->move($dir, $fileName)){
			return $fileName;
		}
		return false;

	}