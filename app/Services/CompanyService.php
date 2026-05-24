<?php

namespace App\Services;

use App\DTOs\Company\CreateCompanyDTO;
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
        try {
            $logoPath = request()->file('logo')->store('tmp', 'public');
            $user=Auth::guard("api")->user();
            dispatch(new ProcessCompanyJob(
                $user,
                $dto->name,
                $dto->email,
                $dto->phone_number,
                $dto->booking_mode,
                $logoPath
            ));

            return $this->success(true,"Company creation process has been started successfully.",[],201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,"Something went wrong while creating the company. Please try again later.".$e->getMessage(),[],500);
        }
    }
}