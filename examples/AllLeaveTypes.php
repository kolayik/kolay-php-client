<?php

require __DIR__ . '/../vendor/autoload.php';

$kolayClient = new Kolay\KolayClient([
	'userCredentials' => ['username' => 'erenakpinar123@gmail.com', 'password' => '123123']
]);
$leaveTypes = $kolayClient->get('leave-type/all');

var_dump($leaveTypes);