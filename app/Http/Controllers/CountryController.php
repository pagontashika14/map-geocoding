<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use DB;
use App\Services\CountryService;

class CountryController extends Controller
{
	protected $countryService;

	public function __construct(CountryService $countryService) {
        $this->countryService = $countryService;
    }

    public function create(Request $request) {
    	return $this->countryService->insert($request->name);
    }

    public function findSimilar(Request $request) {
    	return $this->countryService->findSimilar($request->name);
    }
}
