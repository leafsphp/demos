<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/index.php';

app()->get('/', function () {
	response()->json('Welcome to app. Visit POST /auth/login to login or POST /auth/register to register');
});

app()->group('/auth', function () {
	app()->post('/login', function () {
		$credentials = request()->get(['email', 'password']);
		$user = auth()->login($credentials);

		if (!$user) {
			response()->exit(['status' => 'error', 'data' => auth()->errors()], 401);
		}

		response()->json([
			'status' => 'success',
			'data' => $user
		]);
	});

	app()->post('/register', function () {
		$credentials = request()->get(['username', 'email', 'password']);
		$user = auth()->register($credentials, ['username', 'email']);

		if (!$user) {
			response()->exit([
				'status' => 'error',
				'data' => auth()->errors()
			], 401);
		}

		response()->json([
			'status' => 'success',
			'data' => $user
		], 201);
	});
	
	app()->post('/registerWithOtp', function () {
		auth()->config('HIDE_ID', false);

		$generatedCode = rand(1000, 9999);
		$credentials = request()->get(['username', 'email', 'password']);
		$user = auth()->register($credentials, ['username', 'email']);

		if (!$user) {
			response()->exit([
				'status' => 'error',
				'data' => auth()->errors()
			], 401);
		}

		// Send generatedCode to user's email or something
		// ... if successful then save otp
		db()
			->insert('otps')
			->params([
				'user_id' => $user['user']['id'],
				'code' => $generatedCode
			])
			->execute();

		response()->json([
			'status' => 'success',
			'data' => array_merge($user, [
				// this should not be sent in the response
				'otp' => $generatedCode
			])
		], 201);
	});

	app()->post('/verifyOtp', function () {
		$user = auth()->user();

		if (!$user) {
			response()->exit([
				'status' => 'error',
				'data' => auth()->errors(),
			], 401);
		}

		$otp = db()
			->select('otps')
			->where('user_id', $user['id'])
			->first();

		if ($otp !== request()->get('otp')) {
			response()->exit([
				'status' => 'error',
				'data' => 'Invalid OTP'
			], 401);
		}

		db()
			->delete('otps')
			->where('user_id', $user['id'])
			->execute();

		response()->json([
			'status' => 'success',
			'data' => 'OTP verified'
		], 201);
	});
});

app()->run();
