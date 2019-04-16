<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";
require_once __DIR__ . "/MacUtils.php";

class TrackedInfo
{
    protected $_mac; // 6 bytes, 48-bit
    protected $_macTarget; // 6 bytes, 48 bit
    protected $_signalStrength;
    protected $_lastSeen; // Last seen


    /**
     * Device constructor.
     * @param $mac string
     * @param $ip string
     * @param $type int
     * @param $lastSeen DateTime
     * @param $name string
     */
    function __construct(string $mac, string $macTarget, int $signalStrength, DateTime $lastSeen)
    {
        if (!MacUtils::Validate($mac)) Error("Mac address is invalid");
        if (!MacUtils::Validate($macTarget)) Error("Mac address is invalid");

        $this->_mac = $mac;
        $this->_macTarget = $macTarget;
        $this->_signalStrength = $signalStrength;
        $this->_lastSeen = $lastSeen;
    }

    /**
     * @return string
     */
    public function GetMac(): string
    {
        return $this->_mac;
    }

    /**
     * @return string
     */
    public function GetMacTarget(): string
    {
        return $this->_macTarget;
    }

    /**
     * @return int
     */
    public function GetSignal(): int
    {
        return $this->_signalStrength;
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
     * Gets the device as a php array
     * @return array
     */
    public function GetArray(): array
    {
        return array('mac' => $this->GetMac(), 'macTarget' => $this->GetMacTarget(), 'signal' => $this->GetSignal(), 'lastSeen' => $this->GetLastSeenHumanReadable());
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