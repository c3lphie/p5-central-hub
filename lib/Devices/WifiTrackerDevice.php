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
     * @param $mac int
     * @param $ip int
     * @param $type int
     * @param $lastSeen DateTime
     * @param $name string
     */
    public function __construct(int $mac, int $ip, int $type, DateTime $lastSeen, string $name)
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
            if (is_string($mac))
            {
                $mac = MacUtils::ToInt($mac);
            }
            else
            {
                Error("mac is not an int or a string");
            }
        }



        return -1; //Not implemented
    }
}