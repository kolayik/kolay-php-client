<?php

require __DIR__ . '/../../examples/Authorization.php';

$getCustomFields = json_decode((new Authorization())->get('company/custom-fields'));
$getCustomFields[] = [
    'type' => 'textarea',
    'label' => 'Shoe Size',
    'name' => 'text-15394853'
];
$getCustomFields[] = [
    'type' => 'text',
    'label' => 'Allergies',
    'name' => 'text-43354253'
];
$getCustomFields[] = [
    'type' => 'date',
    'label' => 'Date Time',
    'name' => 'date-19374364'
];

$response = (new Authorization())->post('company/custom-fields', [
    'fields' => $getCustomFields
]);

var_dump($response);
