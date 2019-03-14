<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";
require_once __DIR__ . "/MacUtils.php";
require_once __DIR__ . "/IpUtils.php";

abstract class DeviceType
{
    const WifiTracker = 0;
    const Lock = 1;
}

class Device
{
    protected $_mac; // 6 bytes, 48-bit
    protected $_ip; // 4 bytes, 32-bit
    protected $_type; // What type device it is
    protected $_lastSeen; // Last seen
    protected $_name; // string


    /**
     * Device constructor.
     * @param $mac int
     * @param $ip int
     * @param $type int
     * @param $lastSeen DateTime
     * @param $name string
     */
    function __construct(int $mac, int $ip, int $type, DateTime $lastSeen, string $name)
    {
        $this->_mac = $mac;
        $this->_ip = $ip;
        $this->_type = $type;
        $this->_lastSeen = $lastSeen;
        $this->_name = $name;
    }

    /**
     * @return int
     */
    public function GetMac(): int
    {
        return $this->_mac;
    }


    /**
     * Gets the mac address as a human readable string.
     * Example: AA:BB:CC:DD:EE:FF
     * @return string
     */
    public function GetMacHumanReadable(): string
    {
        return MacUtils::ToString($this->_mac);
    }

    /**
     * @return int
     */
    public function GetIp(): int
    {
        return $this->_ip;
    }

    /**
     * Gets the ip address as a human readable string.
     * Example: 192.168.1.1
     * @return string
     */
    public function GetIpHumanReadable(): string
    {
        return IpUtils::ToString($this->_ip);
    }

    /**
     * @return int
     */
    public function GetType(): int
    {
        return $this->_type;
    }

    /**
     * @return DateTime
     */
    public function GetLastSeen(): DateTime
    {
        return $this->_lastSeen;
    }


    /**
     * Gets the last seen datetime as a human readable string.
     * Example: 2000-01-01 20:10:30
     * @return string
     */
    public function GetLastSeenHumanReadable(): string
    {
        return $this->_lastSeen->format("Y-m-d H:i:s");
    }

    /**
     * @return string
     */
    public function GetName(): string
    {
        return $this->_name;
    }


    /**
     * Gets the device as a php array
     * @return array
     */
    public function GetArray(): array
    {
        return array('mac' => $this->GetMacHumanReadable(), 'ip' => $this->GetIpHumanReadable(), 'type' => $this->GetType(), 'lastSeen' => $this->GetLastSeenHumanReadable(), 'name' => $this->GetName());
    }

    /**
     * Gets the device as json.
     * @return string
     */
    public function GetJson(): string
    {
        $json = json_encode($this->GetArray());
        if ($json == false) Error("GetJson failed");

        return $json;
    }
}