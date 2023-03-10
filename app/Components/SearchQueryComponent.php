<?php

namespace App\Components;

use stdClass;

class SearchQueryComponent
{
    public static function alterQuery($request)
    {

        $data =  json_decode(json_encode($request));
        $query = [];
        foreach ($data as $key => $value) {
            $query[$key] = $value ?? '';
        }

        return $query;
    }
}