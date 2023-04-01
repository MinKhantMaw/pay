<?php

namespace App\Helpers;

use Hashids\Hashids;

class EncodeDecode
{
    public static function  idToHash($id)
    {
        $hashids = new Hashids('this is my test');
        return $hashids->encode($id);
    }

    public static function  hashToId($id)
    {
        $hashids = new Hashids('this is my test');
        return $hashids->decode($id)[0];
    }
}
