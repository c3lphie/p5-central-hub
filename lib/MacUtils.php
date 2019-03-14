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


    /**
     * Converts the mac string representation to an int value.
     * @param $mac string
     * @return int
     */
    public static function ToInt(string $mac):int
    {
        if (!self::Validate($mac)) Error("mac is not valid");

        return (int)base_convert($mac, 16, 10);
    }

    /**
     * Converts a mac in int representation to string representation.
     * @param $mac int
     * @return string
     */
    public static function ToString(int $mac):string
    {
        if ($mac > UINT48_MAX) Error("mac is bigger than a 48-bit int");

        $hex = base_convert($mac, 10, 16);
        while (strlen($hex) < 12)
            $hex = '0' . $hex;
        return strtoupper(implode(':', str_split($hex, 2)));
    }

}