<?php
declare(strict_types=1);

require_once __DIR__ . "/../Error.php";
require_once __DIR__ . "/../MacUtils.php";
require_once __DIR__ . "/../Device.php";
require_once __DIR__ . "/../Database.php";

class WifiLockDevice extends Device
{
    /**
     * WifiLockDevice constructor
     * @param $mac string
     * @param $ip string
     * @param $type int
     * @param $lastSeen DateTime
     * @param $name string
     */
    public function __construct(string $mac, string $ip, int $type, DateTime $lastSeen, string $name)
    {
        if ($type != DeviceType::Lock) Error("type is not Lock");

        parent::__construct($mac, $ip, $type, $lastSeen, $name);
    }
}