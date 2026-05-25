<?php

namespace App\Services;

use App\DTOs\Company\CreateCompanyDTO;
use App\DTOs\Company\UpdateMyCompanyProfileDTO;
use App\Events\CompanyProfileUpdatedEvent;
use App\Events\ProcessCompanyLogoEvent;
use App\Http\Requests\Company\UpdateMyCompanyProfileRequest;
use App\Jobs\ProcessCompanyJob;
use App\Repositories\CompanyRepository;
use App\ReturnTrait;
use Illuminate\Support\Facades\Auth;

class CompanyService{
    use ReturnTrait;
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository=$companyRepository;
    }

    public function createCompany(CreateCompanyDTO $dto)
    {
        try{
            $user=Auth::guard("api")->user();
            $company=$this->companyRepository->createCompany($user,$dto);
            if(request()->file("logo"))
            {
                $logo_path=request()->file("logo")->store("temp","public");
                event(new ProcessCompanyLogoEvent($company,$logo_path));
            }    

            return $this->success(true,"Company created successfully. Logo is being processed.",$company,201);        
        }
        catch(\Exception $e)
        {
            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }


  
}