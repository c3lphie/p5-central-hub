<?php
require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/MacUtils.php";

$error = "";

$mac = strtoupper((string)$_GET["mac"]);

if (!isset($_GET["mac"])) $error = $error . "mac, ";

if ($error != "")
{
    $error = substr($error, 0, -2);
    $error = $error . " get parameter(s) not set";
    die ('{"error": "' . $error . '"}');
}

$db = new Database();

try {
    if (!MacUtils::Validate($mac)) die ('{"error": "mac is invalid"}');

    echo $db->GetDeviceState($mac);
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}