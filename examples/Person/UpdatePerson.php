<?php

require __DIR__ . '/../../examples/Authorization.php';

$updatedPerson = (new Authorization())->post('person/general-info', array (
    'person' =>
        array (
            'id' => 'd6a1ea043834899a671a213a8e46152f',
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

var_dump($updatedPerson);
