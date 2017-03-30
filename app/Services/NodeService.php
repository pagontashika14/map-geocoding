<?php

namespace App\Services;

use App\Node;
use DB;

class NodeService
{
    public function insert($data) {
        $node = new Node;
        $node->latitude = $data['latitude'];
        $node->longitude = $data['longitude'];
        $node->title = $data['title'];
        $node->street_number = $data['street_number'];
        $node->address = $data['address'];
        $node->city_id = $data['city_id'];
        $node->country_id = $data['country_id'];
        $node->user_id = $data['user_id'];
        $node->save();
        return $node;
    }

    public function search($keyword) {
        // $arr = explode(' ',$keyword);
        // $result = [];
        // for($i=0,$len=count($arr);$i<$len;$i++) {
        //     $key = $arr[$i];
        //     $word = DB::table('words')
        //         ->select(DB::raw("name, similarity(name,'$key') as rank"))
        //         ->orderBy('rank','desc')
        //         ->limit(10)
        //         ->get();
        //     return $word;
        //     if($word) array_push($result,$word->name);
        // }
        return DB::table('nodes')
            ->whereRaw("tsv @@ plainto_tsquery('$keyword')")
            ->get();
    }
}
