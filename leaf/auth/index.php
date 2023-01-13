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
});

app()->run();
