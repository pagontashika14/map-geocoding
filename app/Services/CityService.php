<?php

namespace App\Services;

use App\City;
use DB;

class CityService
{
    public function getbyName($name,$country_id) {
        return City::where('country_id',$country_id)
                    ->where('name',$name)->first();
    }

    public function insert($name,$country_id) {
        $city = City::where('country_id',$country_id)
                    ->where('name',$name)->get();
        if(count($city) == 0) {
            $newCity = new City;
            $newCity->name = $name;
            $newCity->country_id = $country_id;
            $newCity->save();
            return $newCity;
        }
        return $city[0];
    }

    public function findSimilar($name) {
        $city = City::select(DB::raw("name, similarity(name,'$name') as rank"))
                ->whereRaw("name % '$name'")
                ->orderBy('rank','desc')
                ->limit(5)
                ->get();
        return $city;
    }
}
