<?php

namespace App;

trait ReturnTrait
{
    public function success($success,$message,$data=null,$code=200)
    {
        return [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "code"=>$code
        ];
    }

    public function error($success,$message,$data=null,$code=500)
    {
        return[
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "code"=>$code
        ];
    }
}
