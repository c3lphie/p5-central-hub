<?php
declare(strict_types=1);

require_once __DIR__ . "/../Error.php";
require_once __DIR__ . "/../MacUtils.php";
require_once __DIR__ . "/../Device.php";
require_once __DIR__ . "/../Database.php";

class WifiTrackerDevice extends Device
{
    /**
     * WifiTrackerDevice constructor.
     * @param $mac string
     * @param $ip string
     * @param $type int
     * @param $lastSeen DateTime
     * @param $name string
     */
    public function __construct(string $mac, string $ip, int $type, DateTime $lastSeen, string $name)
    {
        if ($type != DeviceType::WifiTracker) Error("type is not WifiTracker");

        parent::__construct($mac, $ip, $type, $lastSeen, $name);
    }

    /**
     * Gets the WifiTrackers signal strength to the device specified in $mac.
     * If the returned value is -1. The device is not found.
     * @param $database Database
     * @param $mac int|string
     * @return int
     */
    public function GetSignalStrength(Database $database, $mac): int
    {
        if (!is_int($mac))
        {
            Error("mac is not a string");
        }



        return -1; //Not implemented
    }
}