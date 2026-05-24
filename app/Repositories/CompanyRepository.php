<?php

namespace App\Repositories;

use App\DTOs\Company\CreateCompanyDTO;
use App\Models\Company;

class CompanyRepository{

    public function createCompany(CreateCompanyDTO $dto)
    {
        return Company::create([
            "name"=>$dto->name,
            "email"=>$dto->email,
            "booking_mode"=>$dto->booking_mode,
            "phone_number"=>$dto->phone_number
        ]);
    }

    
}