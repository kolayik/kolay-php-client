<?php

require __DIR__ . '/../examples/Authorization.php';

$leaveTypes = (new Authorization())->get('leave-type/all');

var_dump($leaveTypes);