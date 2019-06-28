<?php

require __DIR__ . '/../../examples/Authorization.php';

$createdPerson = (new Authorization())->post('person/general-info', array (
    'person' =>
        array (
            'firstName' => 'Kolay',
            'lastName' => 'Ipsum',
            'workEmail' => NULL,
            'email' => NULL,
            'employmentType' => 'fulltime',
            'workingDayId' => NULL,
            'accessType' => 'employee',
            'sendWelcomeEmail' => NULL,
            'mobilePhone' => NULL,
            'workPhone' => NULL,
            'tourStatus' => 'passive',
            'managerId' => NULL,
            'employmentStartDate' => '2019/06/28',
        ),
));

var_dump($createdPerson);
