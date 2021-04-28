<?php

namespace App\Http\Controllers;

use App\Services\CompanyDataService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function search()
    {
        return view('search');
    }

    public function find(Request $request, CompanyDataService $companyDataService)
    {
        $regcode = $request->get('regcode') ?? -1;

        if ($regcode === -1) {
            $request->session()->flash('error', "Invalid regcode");
            return redirect('/search');
        }

        return $companyDataService->getByRegcode($regcode);
    }
}
