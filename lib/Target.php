<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";
require_once __DIR__ . "/MacUtils.php";

class Target
{
    protected $_mac; // 6 bytes, 48-bit
    protected $_name; // string


    /**
     * Device constructor.
     * @param $mac string
     * @param $name string
     */
    function __construct(string $mac, string $name)
    {
        if (!MacUtils::Validate($mac)) Error("Mac address is invalid");

        $this->_mac = $mac;
        $this->_name = $name;
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
        return array('mac' => $this->GetMac(), 'name' => $this->GetName());
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