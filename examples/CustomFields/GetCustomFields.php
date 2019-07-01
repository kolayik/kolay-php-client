<?php

require __DIR__ . '/../../examples/Authorization.php';

$getCustomFields = json_decode((new Authorization())->get('company/custom-fields'));

var_dump($getCustomFields);