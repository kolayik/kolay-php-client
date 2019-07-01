<?php

require __DIR__ . '/../../examples/Authorization.php';

$getCustomFields = json_decode((new Authorization())->get('company/custom-fields'));
$getCustomFields[0]->label = 'Shoe Size';

$response = (new Authorization())->post('company/custom-fields', [
    'fields' => $getCustomFields
]);

var_dump($response);
