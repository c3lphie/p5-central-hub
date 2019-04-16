<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/Target.php";
require_once __DIR__ . "/../../lib/MacUtils.php";

$error = "";


$mac = (string)$_GET["mac"];
$name = (string)$_GET["name"];

if (!isset($_GET["mac"])) $error = $error . "mac, ";
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

    $target = new Target($mac, $name);
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}

if ($db->TargetExists($target->GetMac())) die('{"error": "Device already exists"}');

$db->AddTarget($target);