<?php

namespace App\Repositories;

use App\DTOs\Company\CreateCompanyDTO;
use App\Models\Company;

class CompanyRepository{

    public function createCompany($user,CreateCompanyDTO $dto)
    {
        return $user->company()->create([
            "name"=>$dto->name,
            "phone_number"=>$dto->phone_number,
            "email"=>$dto->email
        ]);
    }
}  