<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";
require_once __DIR__ . "/MacUtils.php";

class TrackedDevice
{
    protected $_mac;
    protected $_lastSeen;
    protected $_name;

    /**
     * TrackedDevice constructor.
     * @param $mac string
     * @param DateTime $lastSeen
     * @param string $name
     */
    public function __construct(string $mac, DateTime $lastSeen, string $name)
    {
        $this->_mac = $mac;
        $this->_lastSeen = $lastSeen;
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getMac():string
    {
        return $this->_mac;
    }

    /**
     * @return DateTime
     */
    public function getLastSeen(): DateTime
    {
        return $this->_lastSeen;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }
}