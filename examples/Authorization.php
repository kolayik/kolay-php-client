<?php
require __DIR__ . '/../vendor/autoload.php';

class Authorization extends \Kolay\KolayClient
{
    public function __construct()
    {
        parent::__construct([
            'userCredentials' => ['username' => 'erenakpinar123@gmail.com', 'password' => '123123']
        ]);
    }
}
