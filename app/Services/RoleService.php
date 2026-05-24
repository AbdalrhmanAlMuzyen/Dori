<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use App\ReturnTrait;

class RoleService
{
    use ReturnTrait;

    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRoles()
    {
        try {

            $roles = $this->roleRepository->getRoles();

            if ($roles->isEmpty()) {
                return $this->success(true, "No roles found", []);
            }

            return $this->success(true, "Roles retrieved successfully", $roles);

        } catch (\Exception $e) {

            return $this->error(false, $e->getMessage(), [], 500);

        }
    }
}