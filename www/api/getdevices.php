<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/Device.php";

$db = new Database();

$array = array();

/** @var Device $device */
foreach ($db->GetDevices() as $device)
{
    array_push($array, $device->GetArray());
}

echo json_encode($array);