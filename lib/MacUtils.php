<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";

const UINT48_MAX = 0xFFFFFFFFFFFF;

abstract class MacUtils
{
    /**
     * Returns true if the mac-address is valid.
     * False otherwise.
     * @param $mac string
     * @return bool
     */
    public static function Validate(string $mac):bool
    {
        if (strlen($mac) != 17) return false;

        return preg_match("/[0-9A-F][0-9A-F][:][0-9A-F][0-9A-F][:][0-9A-F][0-9A-F][:][0-9A-F][0-9A-F][:][0-9A-F][0-9A-F][:][0-9A-F][0-9A-F]/", $mac) == 1;
    }
}