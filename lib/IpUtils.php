<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";


const UINT32_MAX = 0xFFFFFFFF;

abstract class IpUtils
{
    /**
     * Returns true if the ip-address is valid.
     * False otherwise.
     * @param $ip string
     * @return bool
     */
    public static function Validate(string $ip):bool
    {
        $ipLength = strlen($ip);

        if ($ipLength > 15 || $ipLength < 7) return false;

        return preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $ip) == 1;
    }

    /**
     * Converts the ip string representation to an int value.
     * @param $ip string
     * @return int
     */
    public static function ToInt(string $ip):int
    {
        if (!self::Validate($ip)) Error("ip is not valid");

        return ip2long($ip);
    }

    /**
     * Converts a ip in int representation to string representation.
     * @param $ip int
     * @return string
     */
    public static function ToString(int $ip):string
    {
        if ($ip > UINT32_MAX) Error("ip is bigger than a 32-bit int");

        return long2ip($ip);
    }
}