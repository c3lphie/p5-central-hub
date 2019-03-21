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
}