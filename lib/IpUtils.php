<?php

require_once __DIR__ . "/Error.php";


const UINT32_MAX = 4294967295;

abstract class IpUtils
{
    /**
     * Returns true if the ip-address is valid.
     * False otherwise.
     * @param $ip string
     * @return bool
     */
    public static function Validate($ip):bool
    {
        if (!is_string($ip)) Error("ip is not a string");

        $ipLength = strlen($ip);

        if ($ipLength > 15 || $ipLength < 7) return false;

        return preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $ip) == 1;
    }

    /**
     * Converts the ip string representation to an int value.
     * @param $ip string
     * @return int
     */
    public static function ToInt($ip):int
    {
        if (!is_string($ip)) Error("ip is not a string");

        if (!self::Validate($ip)) Error("ip is not valid");

        return ip2long($ip);
    }

    /**
     * Converts a ip in int representation to string representation.
     * @param $ip int
     * @return string
     */
    public static function ToString($ip):string
    {
        if ($ip > UINT32_MAX) Error("ip is bigger than a 32-bit int");

        return long2ip($ip);
    }
}