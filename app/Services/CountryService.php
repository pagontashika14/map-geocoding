<?php

namespace App\Services;

use App\Country;
use DB;

class CountryService
{
    public function getbyName($name) {
        return Country::where('name',$name)->first();
    }

    public function insert($name) {
        $country = Country::where('name',$name)->get();
        if(count($country) == 0) {
            $newCountry = new Country;
            $newCountry->name = $name;
            $newCountry->save();
            return $newCountry;
        }
        return $country[0];
    }

    public function findSimilar($name) {
        $country = Country::select(DB::raw("name, similarity(name,'$name') as rank"))
                ->whereRaw("name % '$name'")
                ->orderBy('rank','desc')
                ->limit(5)
                ->get();
        return $country;
    }
}
