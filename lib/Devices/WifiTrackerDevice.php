<?php
require_once __DIR__ . '/../Device.php';
require_once __DIR__ . '/../Database.php';

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
    public function __construct($mac, $ip, $type, $lastSeen, $name)
    {
        if ($type != DeviceType::WifiTracker) die("type is not WifiTracker");

        parent::__construct($mac, $ip, $type, $lastSeen, $name);
    }

    /**
     * Gets the WifiTrackers signal strength to the device specified in $mac.
     * If the returned value is -1. The device is not found.
     * @param $database Database
     * @param $mac int
     * @return int
     */
    public function GetSignalStrength($database, $mac): int
    {
        if (!is_a($database, 'Database')) die("database is not a Database");
        if (!is_int($mac)) die("mac is not an int");

        return -1; //Not implemented
    }
}