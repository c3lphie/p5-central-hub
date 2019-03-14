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
     * @param $mac int|string
     * @param string $name
     */
    public function __construct($mac, DateTime $lastSeen, string $name)
    {
        if (!is_int($mac)) {
            if (is_string($mac)) {
                if (!MacUtils::Validate($mac)) Error("mac is not valid");

                $mac = MacUtils::ToInt($mac);

            } else {
                Error("mac is not a valid type (int|string)");
            }
        }

        $this->_mac = $mac;
        $this->_lastSeen = $lastSeen;
        $this->_name = $name;
    }

    /**
     * @return int
     */
    public function getMac():int
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