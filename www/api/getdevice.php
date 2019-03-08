<?php

if (!isset($_GET["mac"])) die ('{"error": "mac get parameter not set"}');

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/Device.php";

$db = new Database();

if ($db->DeviceExistsMac($_GET["mac"]))
{
    $device = $db->GetDevice($_GET["mac"]);

    echo $device->GetJson();
}
else
{
    echo '{"error": "Device not found"}';
}
