<?php

namespace App\Http\Controllers;

use App\DTOs\Company\CreateCompanyDTO;
use App\Http\Requests\Company\CreateCompanyRequest;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService=$companyService;
    }

    public function createCompany(CreateCompanyRequest $request)
    {
        $result=$this->companyService->createCompany(CreateCompanyDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);        
    }
}
