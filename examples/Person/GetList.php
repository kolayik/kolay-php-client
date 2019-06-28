<?php

require __DIR__ . '/../../examples/Authorization.php';

$getList = (new Authorization())->post('person/search', array(
            'page' => 1,
            'q' => 'Lorem',
            'status'=> true,
            'units'=> [
                [
                    '901e0cd99b8215ccc230736e1c05d481'
                ],
                [
                    '8b7c3be0169b80d102860d142c82b294'
                ]
            ]
     ));

var_dump($getList);