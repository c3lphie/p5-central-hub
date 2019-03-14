<?php
require_once __DIR__ . "/Error.php";
require_once __DIR__ . "/Device.php";
require_once __DIR__ . "/MacUtils.php";


class Database
{
    private static $databaseHost = "localhost";
    private static $databaseUsername = "root";
    private static $databasePassword = "toor";
    private static $databaseDatabase = "CentralHub";

    private $_conn;

    function __construct()
    {
        $this->_conn = self::GetConnection();
    }

    /**
     * Creates a connection to the mysql server.
     * @return mysqli
     */
    private static function GetConnection(): mysqli
    {
        $conn = new mysqli(self::$databaseHost, self::$databaseUsername, self::$databasePassword, self::$databaseDatabase);

        if ($conn->connect_error) {
            Error($conn->connect_error);
        }

        return $conn;
    }

    /**
     * Adds a Device to the database.
     * Warning: This function does not check for existing device.
     * @param $device Device
     */
    public function AddDevice($device): void
    {
        if (!is_a($device, "Device") && !is_subclass_of($device, "Device")) Error("device is not a Device or a subclass of Device");


        $mac = $this->_conn->real_escape_string($device->GetMac());
        $ip = $this->_conn->real_escape_string($device->GetIp());
        $type = $this->_conn->real_escape_string($device->GetType());
        $lastSeen = $this->_conn->real_escape_string($device->GetLastSeen()->format("Y-m-d H:i:s"));
        $name = $this->_conn->real_escape_string($device->GetName());

        $sql = "INSERT INTO Devices (Mac, Ip, Type, LastSeen, Name) VALUES ('$mac', '$ip', '$type', '$lastSeen', '$name')";

        if (!$this->_conn->query($sql)) Error("AddDevice failed: " . $this->_conn->error);
    }

    /**
     * Updates an existing Device.
     * Warning: This function does not check if the device is existing.
     * @param $device Device
     */
    public function UpdateDevice($device): void
    {
        if (!is_a($device, "Device") && !is_subclass_of($device, "Device")) Error("device is not a Device or a subclass of Device");

        $mac = $this->_conn->real_escape_string($device->GetMac());
        $ip = $this->_conn->real_escape_string($device->GetIp());
        $type = $this->_conn->real_escape_string($device->GetType());
        $lastSeen = $this->_conn->real_escape_string($device->GetLastSeen()->format("Y-m-d H:i:s"));
        $name = $this->_conn->real_escape_string($device->GetName());

        $sql = "UPDATE Devices SET Ip='$ip', Type='$type', LastSeen='$lastSeen', Name='$name' WHERE Mac=$mac";

        if (!$this->_conn->query($sql)) Error("UpdateDevice failed: " . $this->_conn->error);
    }


    /**
     * A safe way to update or add a device.
     * If the device exists it updates it.
     * If it does not exist it adds it.
     * @param $device Device
     */
    public function UpdateOrAddDevice($device):void
    {
        if ($this->DeviceExists($device))
        {
            $this->UpdateDevice($device);
        }
        else
        {
            $this->AddDevice($device);
        }
    }

    /**
     * Checks if a device exists.
     * @param $device Device
     * @return bool
     */
    public function DeviceExists($device): bool
    {
        if (!is_a($device, "Device") && !is_subclass_of($device, "Device")) Error("device is not a Device or a subclass of Device");

        return $this->DeviceExistsMac($device->GetMac());
    }

    /**
     * Checks if a device exists.
     * @param $mac int
     * @return bool
     */
    public function DeviceExistsMac($mac): bool
    {
        if (!is_int($mac)) {
            if (is_string($mac)) {
                $mac = MacUtils::ToInt($mac);

            } else {
                Error("mac is not a valid type (int|string)");
            }
        }
        $mac = $this->_conn->real_escape_string($mac);

        $sql = "SELECT 1 FROM Devices WHERE Mac='$mac' LIMIT 1";

        if ($this->_conn->query($sql)->fetch_row() == null) {
            return false;
        }

        return true;
    }

    /**
     * Gets a device.
     * Warning: This function does not check if the device exists first.
     * @param $mac int
     * @return Device
     */
    public function GetDevice($mac): Device
    {
        if (!is_int($mac))
        {
            if (is_string($mac))
            {
                $mac = MacUtils::ToInt($mac);
            }
            else
            {
                Error("mac is not a valid type (int|string)");
            }
        }

        $mac = $this->_conn->real_escape_string($mac);

        $sql = "SELECT Mac, Ip, Type, LastSeen, Name FROM Devices WHERE Mac='$mac' LIMIT 1";

        $row = $this->_conn->query($sql)->fetch_row();

        if ($row == null)
            Error("GetDevice failed: " . $this->_conn->error);

        $mac = (int)$row[0];
        $ip = (int)$row[1];
        $type = (int)$row[2];
        $lastSeen = DateTime::createFromFormat("Y-m-d H:i:s", $row[3]);
        $name = $row[4];


        $device = new Device($mac, $ip, $type, $lastSeen, $name);

        return $device;

    }

    /**
     * Gets all devices as an array.
     * @return array
     */
    public function GetDevices(): array
    {

        $sql = "SELECT Mac, Ip, Type, LastSeen, Name FROM Devices";


        $devices = array();

        $query = $this->_conn->query($sql);

        while ($row = $query->fetch_assoc()) {

            $mac = (int)$row["Mac"];
            $ip = (int)$row["Ip"];
            $type = (int)$row["Type"];
            $lastSeen = DateTime::createFromFormat("Y-m-d H:i:s", $row["LastSeen"]);
            $name = $row["Name"];


            array_push($devices, new Device($mac, $ip, $type, $lastSeen, $name));
        }


        return $devices;

    }
}


