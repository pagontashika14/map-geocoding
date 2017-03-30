<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Services\CityService;

class CityController extends Controller
{
    protected $cityService;

	public function __construct(CityService $cityService) {
        $this->cityService = $cityService;
    }

    public function create(Request $request) {
    	return $this->cityService->insert($request->name,$request->country_id);
    }

    public function findSimilar(Request $request) {
    	return $this->cityService->findSimilar($request->name);
    }
}
