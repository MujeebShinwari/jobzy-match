<?php

	namespace App\Http\Middleware;

	use App\User;
	use Closure;
	use Illuminate\Http\Response;

	class VerifyToken
	{
		/**
		 * Handle an incoming request.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \Closure $next
		 * @return mixed
		 */
		public function handle($request, Closure $next)
		{
			// Verify user token
			if ($request->has('token') == false) {
				return response()->json([
					'status_code' => 400,
					'message'     => 'Authentication token is required'
				]);
			} else {
				$user = User::whereAuthToken($request->get('token'))->first();
				if (!$user) {
					return response()->json([
						'status_code' => 401,
						'message'     => 'Authentication token is invalid'
					]);
				}
			}
			return $next($request);
		}
	}
