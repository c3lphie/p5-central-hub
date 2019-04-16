<?php
declare(strict_types=1);

require_once __DIR__ . "/Error.php";
require_once __DIR__ . "/MacUtils.php";
require_once __DIR__ . "/Device.php";
require_once __DIR__ . "/TrackedDevice.php";
require_once __DIR__ . "/TrackedInfo.php";


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

        $statement->bind_param("sisss", $device->GetIp(), $device->GetType(), $device->GetLastSeen()->format("Y-m-d H:i:s"), $device->GetName(), $device->GetMac());


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

        $statement->bind_param("s", $mac);

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

        $statement->bind_param("s", $mac);

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
     * Adds a Target to the database.
     * Warning: This function does not check for existing device.
     * @param $trackedInfo TrackedInfo
     */
    public function AddTrackedInfo(TrackedInfo $trackedInfo): void 
    {
        $statement = $this->_conn->prepare("INSERT INTO Scandb (Mac, MacTarget, SignalStrength, LastSeen) VALUES (?, ?, ?, ?)");

        if ($statement == false) Error("Could not create statement");

        $statement->bind_param("ssis", $trackedInfo->GetMac(), $trackedInfo->GetMacTarget(), $trackedInfo->GetSignal(), $trackedInfo->GetLastSeen()->format("Y-m-d H:i:s"));


        if (!$statement->execute()) Error("AddTargetScan failed: " . $statement->error);
    }

    /**
     * Checks if a device exists.
     * @param $mac string
     * @param $macTarget string
     * @return bool
     */
    public function TrackedInfoExists(string $macTarget, string $mac): bool
    {
        $statement = $this->_conn->prepare("SELECT 1 FROM Scandb WHERE Mac=? AND MacTarget=? LIMIT 1");

        $statement->bind_param("ss", $mac, $macTarget);

        if (!$statement->execute()) Error("TrackedInfoExists failed");

        return $statement->fetch() != null;
    }

    /**
     * Updates existing trackedinfo.
     * Warning: This function does not check if the trackedinfo exists.
     * @param $trackedInfo TrackedInfo
     */
    public function UpdateTrackedInfo(TrackedInfo $trackedInfo): void
    {
        $statement = $this->_conn->prepare("UPDATE Scandb SET SignalStrength=?, LastSeen=? WHERE MacTarget=? AND Mac=?");

        $statement->bind_param("isss", $trackedInfo->GetSignal(), $trackedInfo->GetLastSeen()->format("Y-m-d H:i:s"), $trackedInfo->GetMacTarget(),$trackedInfo->GetMac());


        if (!$statement->execute()) Error("UpdateDevice failed: " . $statement->error);
    }

    /**
     * A safe way to update or add a trackedinfo.
     * If the trackedinfo exists it updates it.
     * If it does not exist it adds it.
     * @param $trackedInfo TrackedInfo
     */
    public function UpdateOrAddTrackedInfo(TrackedInfo $trackedInfo):void
    {
        if ($this->TrackedInfoExists($trackedInfo->GetMac(), $trackedInfo->GetMacTarget()))
        {
            error_log($this->TrackedInfoExists($trackedInfo->GetMac(), $trackedInfo->GetMacTarget()))
            $this->UpdateTrackedInfo($trackedInfo);
        }
        else
        {
            error_log($this->TrackedInfoExists($trackedInfo->GetMac(), $trackedInfo->GetMacTarget()))
            $this->AddTrackedInfo($trackedInfo);
        }
    }


}


