<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimecalcController extends Controller
{
    public static function MinToString($min) {
       

        //$stunden = number_format($min/60,0);
        $stunden = floor($min/60);
        $minuten = $min%60;
        $negativ='';
        if($min<0) {
            $negativ = '- ';
        }
        if($stunden<0) {
            $stunden = substr($stunden,1,strlen($stunden));
        }
        if($minuten<0) {
            $minuten = substr($minuten,1,strlen($minuten));
        }

        if(strlen($stunden)==1) {
            $stunden = '0'.$stunden;
        }
        if(strlen($minuten)==1) {
            $minuten = '0'.$minuten;
        }
       
        $ausgabe = $negativ.$stunden.':'.$minuten;
        return $ausgabe;
    }
        /**
     * H:i to Minute
     */
    public static function StringToMin($str) {

        if($str=="") {
            return 0;
        }
        $h = (int)substr($str,0,2);
        $m = (int)substr($str,3,2);
        return $h*60+$m;

    }
}
