<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/Device.php";
require_once __DIR__ . "/../../lib/MacUtils.php";
require_once __DIR__ . "/../../lib/IpUtils.php";

$error = "";


$mac = strtoupper((string)$_GET["mac"]);
$ip = (string)$_GET["ip"];
$type = (int)$_GET["type"];
$name = (string)$_GET["name"];

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
    if (!MacUtils::Validate($mac)) die ('{"error": "mac is invalid"}');
    if (!IpUtils::Validate($ip)) die ('{"error": "ip is invalid"}');

    $device = new Device($mac, $ip, $type, new DateTime("now"), $name);
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}
$db->UpdateOrAddDevice($device);