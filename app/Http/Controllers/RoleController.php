<?php

namespace App\Http\Controllers;

use App\Services\RoleService;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService=$roleService;
    }

    public function getRoles()
    {
        $result=$this->roleService->getRoles();
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);        
    }
}
