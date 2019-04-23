<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 * Description of Functions
 *
 * @author Granik
 */
class Functions {
    
    public static function toShortDate($date_string) {
        $pattern = "/^(\d\d\d\d)-(\d\d)-(\d\d)$/";
        $Ok = preg_match($pattern, $date_string, $m);
        if(!$Ok) return $date_string;
        return $m[3]. '.' . $m[2];
    }
    
    public static function toSovietDate($date_string) {
        $pattern = "/^(\d\d\d\d)-(\d\d)-(\d\d)$/";
        $Ok = preg_match($pattern, $date_string, $matches);
        if(!$Ok) return $date_string;
        unset($matches[0]);
        $result = implode(array_reverse($matches), '.');
        return $result;
    }
    
    public static function withoutSec($time_string) {
        $pattern = "/^(\d\d):(\d\d):(\d\d)$/";
        $Ok = preg_match($pattern, $time_string, $m);
        if(!$Ok) return $time_string;
        return $m[1] . ":" . $m[2];
    }
    
    public static function toDBdate($date_string) {
        $pattern = "/^(\d\d)\.(\d\d)\.(\d\d\d\d)$/";
        $Ok = preg_match($pattern, $date_string, $matches);
        if(!$Ok) return $date_string;
        unset($matches[0]);
        $result = implode(array_reverse($matches), '-');
        return $result;
    }
}
