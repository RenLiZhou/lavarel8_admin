<?php

namespace App\Exceptions;


use App\Common\Enum\AdminCode;
use Exception;

class AdminException extends Exception
{
    public function __construct(string $error)
    {
        $this->message = AdminCode::getMsg($error);
        $this->code = AdminCode::getCode($error);
    }

}
