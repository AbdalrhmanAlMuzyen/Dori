<?php
namespace App\Repositories;

use App\Models\Role;


class RoleRepository{

    publiC function getRoles()
    {
        return Role::where("name","!=","super_admin")->get();
    }
}