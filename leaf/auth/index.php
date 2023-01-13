<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/index.php';

app()->get('/', function () {
	response()->json('Welcome to app. Visit POST /login to login or POST /register to register');
});

app()->post('/login', function () {
	$credentials = request()->get(['email', 'password']);
	$user = auth()->login($credentials);

	if (!$user) {
		response()->exit(auth()->errors(), 401);
	}

	response()->json($user);
});

app()->run();
