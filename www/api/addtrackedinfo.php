<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/TrackedInfo.php";
require_once __DIR__ . "/../../lib/MacUtils.php";

$error = "";

if (!isset($_GET["mac"])) $error = $error . "mac, ";
if (!isset($_GET["mactarget"])) $error = $error . "mactarget, ";
if (!isset($_GET["signalstrength"])) $error = $error . "signalstrength, ";

$mac = (string)$_GET["mac"];
$macTarget = (string)$_GET["mactarget"];
$signal = (int)$_GET["signalstrength"]


if ($error != "")
{
    $error = substr($error, 0, -2);
    $error = $error . " get parameter(s) not set";
    die ('{"error": "' . $error . '"}');
}



$db = new Database();

try {
    if (!MacUtils::Validate($mac)) die ('{"error": "mac is invalid"}');
    if (!MacUtils::Validate($macTarget)) die ('{"error": "mactarget is invalid"}');

    $trackedInfo = new TrackedInfo($mac, $macTarget, $signalStrength, new DateTime("now"));
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}

if ($db->TrackedInfoExists($trackedInfo->GetMac())) die('{"error": "Device already exists"}');

$db->AddTarget($trackedInfo);