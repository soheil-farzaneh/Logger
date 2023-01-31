<?php

namespace Aqayepardakht\Logger;

class Curl{
    
    public static function execute($value){
        $url = config('telescope.url');
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, ['data' => json_encode($value)]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('content-Type','application/json'));
        $result=curl_exec($ch);
        print_r($result);
            // dd($productsdata);die();
        return curl_close($ch);

    }
}

