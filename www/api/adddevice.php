<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/Device.php";

$error = "";

if (!isset($_GET["mac"])) $error = $error . "mac, ";
if (!isset($_GET["ip"])) $error = $error . "ip, ";
if (!isset($_GET["type"])) $error = $error . "type, ";
if (!isset($_GET["name"])) $error = $error . "name, ";


if ($error != "")
{
    $error = substr($error, 0, -2);
    $error = $error . " get parameter(s) not set";
    die ('{"error": "' . $error . '"}');
}



$db = new Database();

try {
    $device = new Device((string)$_GET["mac"], (string)$_GET["ip"], (int)$_GET["type"], new DateTime("now"), (string)$_GET["name"]);
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}

if ($db->DeviceExists($device)) die('{"error": "Device already exists"}');

$db->AddDevice($device);