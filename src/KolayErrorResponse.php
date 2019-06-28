<?php

namespace Kolay;

class KolayErrorResponse
{

    /**
     * @var integer $code
     */
    public $code;

    /**
     * @var string $message
     */
    public $message;

    /**
     * @var array $details
     */
    public $details;

    public function __construct($code = 0, $message = 'Error', $details = [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->details = $details;
    }
}