<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NodeService;
use App\Services\CountryService;
use App\Services\CityService;
use App\Services\HelperService;

class NodeController extends Controller
{
	protected $nodeService;
	protected $countryService;
	protected $cityService;
	protected $_h;

    public function __construct(NodeService $nodeService,
    							CountryService $countryService,
    							CityService $cityService,
    							HelperService $h
    							) {
    	$this->nodeService = $nodeService;
    	$this->countryService = $countryService;
    	$this->cityService = $cityService;
    	$this->_h = $h;
    }

    public function create(Request $request) {
    	$user = $request->user();
    	$city_id = null;
    	$country_id = null;
    	if($this->_h->check($request->city) && $this->_h->check($request->country)) {
    		$country = $this->countryService->insert($request->country);
    		$city = $this->cityService->insert($request->city,$country->id);
    		$country_id = $country->id;
    		$city_id = $city->id;
    	} else if($this->_h->check($request->country)) {
    		$country_id = $this->countryService->insert($request->country)->id;
    	}
    	$data = [
    		'title' => $request->title,
    		'street_number' => $request->street_number ? $request->street_number : null,
    		'address' => $request->address,
    		'city_id' => $city_id,
    		'country_id' => $country_id,
    		'latitude' => $request->latitude,
    		'longitude' => $request->longitude,
    		'user_id' => $user->id,
            'description' => $request->description
    	];
    	return $this->nodeService->insert($data);
    }

    public function search(Request $request) {
    	return $this->nodeService->search($request->keyword);
    }
}
