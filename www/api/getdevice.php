<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/Device.php";
require_once __DIR__ . "/../../lib/MacUtils.php";


if (!isset($_GET["mac"])) die ('{"error": "mac get parameter not set"}');
$mac = (string)$_GET["mac"];

$db = new Database();

if (!MacUtils::Validate($mac)) die ('{"error": "mac is invalid"}');

$mac = MacUtils::ToInt($mac);

if (!$db->DeviceExists($mac)) die ('{"error": "Device not found"}');

$device = $db->GetDevice($mac);

echo $device->GetJson();

