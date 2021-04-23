<?php

namespace Helpers;

class Config {

    const CONFIG_DELIMITER = '.';

    public static function get(string $array) {

        $path = realpath(dirname(__FILE__, 3)).'/config/';
        $parts = explode(self::CONFIG_DELIMITER, strtolower($array));
        $keys = substr($array, strlen($parts[0]) + 1);

        if(file_exists($path . $parts[0] . '.php')) {
            $array = require $path . $parts[0] . '.php';
            
            if(count($parts) == 1) {
                return $array;
            } else {
                foreach (explode(self::CONFIG_DELIMITER, $keys) as $segment) {
                    if (! is_array($array) || ! array_key_exists($segment, $array)) {
                        return $array;
                    }
                    $array = $array[$segment];
                }
                return $array;
            }
            
        } else {
            return false;
        }
    }

}