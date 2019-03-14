<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";
require_once __DIR__ . "/MacUtils.php";
require_once __DIR__ . "/Device.php";
require_once __DIR__ . "/TrackedDevice.php";


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

        if ($conn->connect_error) Error($conn->connect_error);

        if (!$conn->set_charset("utf8mb4")) Error("Setting charset failed");


        return $conn;
    }

    /**
     * Adds a Device to the database.
     * Warning: This function does not check for existing device.
     * @param $device Device
     */
    public function AddDevice(Device $device): void
    {
        $mac = $this->_conn->real_escape_string((string)$device->GetMac());
        $ip = $this->_conn->real_escape_string((string)$device->GetIp());
        $type = $this->_conn->real_escape_string((string)$device->GetType());
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
    public function UpdateDevice(Device $device): void
    {
        $mac = $this->_conn->real_escape_string((string)$device->GetMac());
        $ip = $this->_conn->real_escape_string((string)$device->GetIp());
        $type = $this->_conn->real_escape_string((string)$device->GetType());
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
    public function UpdateOrAddDevice(Device $device):void
    {
        if ($this->DeviceExists($device->GetMac()))
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
     * @param $mac int
     * @return bool
     */
    public function DeviceExists(int $mac): bool
    {
        $mac = $this->_conn->real_escape_string((string)$mac);

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
    public function GetDevice(int $mac): Device
    {
        $mac = $this->_conn->real_escape_string((string)$mac);

        $sql = "SELECT Mac, Ip, Type, LastSeen, Name FROM Devices WHERE Mac='$mac' LIMIT 1";

        $row = $this->_conn->query($sql)->fetch_row();

        if ($row == null)
            Error("GetDevice failed: " . $this->_conn->error);

        $mac = (int)$row[0];
        $ip = (int)$row[1];
        $type = (int)$row[2];
        $lastSeen = DateTime::createFromFormat("Y-m-d H:i:s", $row[3]);
        $name = $row[4];


        return new Device($mac, $ip, $type, $lastSeen, $name);
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

    /**
     * @param $mac int
     * @return TrackedDevice
     */
    public function GetTrackedDevice(int $mac):TrackedDevice
    {
        $mac = $this->_conn->real_escape_string((string)$mac);

        $sql = "SELECT Mac, LastSeen Name FROM TrackedDevices WHERE Mac='$mac' LIMIT 1";

        $row = $this->_conn->query($sql)->fetch_row();

        if ($row == null)
            Error("GetTrackedDevice failed: " . $this->_conn->error);

        $mac = (int)$row[0];
        $lastSeen = DateTime::createFromFormat("Y-m-d H:i:s", (string)$row[1]);
        $name = (string)$row[2];

        return new TrackedDevice($mac, $lastSeen, $name);
    }
}


