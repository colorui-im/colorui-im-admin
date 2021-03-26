<?php

namespace App\Exceptions;

use Exception;

class RuntimeException extends Exception
{
    public function render($request)
    {
        return response()->json(['code'=>1,'msg'=>$this->getMessage(),'data'=>[]]);
    }
}
