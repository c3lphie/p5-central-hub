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
        $statement = $this->_conn->prepare("INSERT INTO Devices (Mac, Ip, Type, LastSeen, Name) VALUES (?, ?, ?, ?, ?)");

        if ($statement == false) Error("Could not create statement");

        $statement->bind_param("ssiss", $device->GetMac(), $device->GetIp(), $device->GetType(), $device->GetLastSeen()->format("Y-m-d H:i:s"), $device->GetName());


        if (!$statement->execute()) Error("AddDevice failed: " . $statement->error);
    }

    /**
     * Adds a Target to the database.
     * Warning: This function does not check for existing device.
     * @param $target Target
     */
    public function AddTarget(Target $target): void
    {
        $statement = $this->_conn->prepare("INSERT INTO Targets (Mac, Name) VALUES (?, ?)");

        if ($statement == false) Error("Could not create statement");

        $statement->bind_param("ss", $target->GetMac(), $target->GetName());


        if (!$statement->execute()) Error("AddTarget failed: " . $statement->error);
    }

    /**
     * Updates an existing Device.
     * Warning: This function does not check if the device is existing.
     * @param $device Device
     */
    public function UpdateDevice(Device $device): void
    {
        $statement = $this->_conn->prepare("UPDATE Devices SET Ip=?, Type=?, LastSeen=?, Name=? WHERE Mac=?");

        $statement->bind_param("iissi", $device->GetIp(), $device->GetType(), $device->GetLastSeen()->format("Y-m-d H:i:s"), $device->GetName(), $device->GetMac());


        if (!$statement->execute()) Error("UpdateDevice failed: " . $statement->error);
    }

    /**
     * Updates an existing Device.
     * Warning: This function does not check if the device is existing.
     * @param $target Target
     */
    public function UpdateTarget(Target $target): void
    {
        $statement = $this->_conn->prepare("UPDATE Targets SET Name=? WHERE Mac=?");

        $statement->bind_param("ss", $target->GetName(), $target->GetMac());


        if (!$statement->execute()) Error("UpdateDevice failed: " . $statement->error);
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
     * @param $mac string
     * @return bool
     */
    public function DeviceExists(string $mac): bool
    {
        $statement = $this->_conn->prepare("SELECT 1 FROM Devices WHERE Mac=? LIMIT 1");

        $statement->bind_param("i", $mac);

        if (!$statement->execute()) Error("DeviceExists failed");

        return $statement->fetch() != null;
    }

    /**
     * Checks if a device exists.
     * @param $mac string
     * @return bool
     */
    public function TargetExists(string $mac): bool
    {
        $statement = $this->_conn->prepare("SELECT 1 FROM Targets WHERE Mac=? LIMIT 1");

        $statement->bind_param("i", $mac);

        if (!$statement->execute()) Error("DeviceExists failed");

        return $statement->fetch() != null;
    }

    /**
     * Gets a device.
     * Warning: This function does not check if the device exists first.
     * @param $mac string
     * @return Device
     */
    public function GetDevice(string $mac): Device
    {
        $statement = $this->_conn->prepare("SELECT Mac, Ip, Type, LastSeen, Name FROM Devices WHERE Mac=? LIMIT 1");

        if ($statement == false) Error("Could not create statement");

        $statement->bind_param("s", $mac);

        if (!$statement->execute()) Error("GetDevice failed: " . $statement->error);

        /** @var string $mac */
        /** @var string $ip */
        /** @var int $type */
        /** @var string $lastSeen */
        /** @var string $name */
        $statement->bind_result($mac, $ip, $type, $lastSeen, $name);

        if (!$statement->fetch()) Error("Device does not exist");

        $datetime = DateTime::createFromFormat("Y-m-d H:i:s", $lastSeen);

        if (!$datetime) Error("Could not convert datetime");

        return new Device($mac, $ip, $type, $datetime, $name);
    }

    /**
     * Gets all devices as an array.
     * @return array
     */
    public function GetDevices(): array
    {
        $statement = $this->_conn->prepare("SELECT Mac, Ip, Type, LastSeen, Name FROM Devices");

        if (!$statement->execute()) Error("GetDevices failed");

        /** @var string $mac */
        /** @var string $ip */
        /** @var int $type */
        /** @var string $lastSeen */
        /** @var string $name */
        $statement->bind_result($mac, $ip, $type, $lastSeen, $name);

        $devices = array();
        while ($statement->fetch()) {
            $datetime = DateTime::createFromFormat("Y-m-d H:i:s", $lastSeen);

            if (!$datetime) Error("Could not convert datetime");
            array_push($devices, new Device($mac, $ip, $type, $datetime, $name));
        }
        return $devices;
    }

    /**
     * Gets all targets as an array.
     * @return array
     */
    public function GetTargets(): array
    {
        $statement = $this->_conn->prepare("SELECT Mac, Name FROM Targets");

        if (!$statement->execute()) Error("GetTargets failed");

        /** @var string $mac */
        /** @var string $name */
        $statement->bind_result($mac, $name);

        $targets = array();
        while ($statement->fetch()) {
            array_push($targets, new Target($mac, $name));
        }
        return $targets;
    }

    /**
     * @param $mac int
     * @return TrackedDevice
     */
    public function GetTrackedDevice(int $mac):TrackedDevice
    {

        $statement = $this->_conn->prepare("SELECT Mac, LastSeen, Name FROM TrackedDevices WHERE Mac=? LIMIT 1");

        $statement->bind_param("i", $mac);

        if (!$statement->execute()) Error("GetTrackedDevice failed: " . $statement->error);

        /** @var int $mac */
        /** @var string $lastSeen */
        /** @var string $name */
        $statement->bind_result($mac, $lastSeen, $name);

        if (!$statement->fetch()) Error("TrackedDevice does not exist");

        $datetime = DateTime::createFromFormat("Y-m-d H:i:s", $lastSeen);

        if (!$datetime) Error("Could not convert datetime");

        return new TrackedDevice($mac, $datetime, $name);
    }
}


