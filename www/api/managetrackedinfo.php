<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/TrackedInfo.php";
require_once __DIR__ . "/../../lib/MacUtils.php";

$error = "";


$mac = (string)$_GET["mac"];
$macTarget = (string)$_GET["mactarget"];
$signal = (int)$_GET["signalstrength"];

if (!isset($_GET["mac"])) $error = $error . "mac, ";
if (!isset($_GET["mactarget"])) $error = $error . "mactarget, ";
if (!isset($_GET["signalstrength"])) $error = $error . "signalstrength, ";

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

    $trackedInfo = new TrackedInfo($mac, $macTarget, $signal, new DateTime("now"));
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}

$oldLastSeen = $db->GetOldLastSeen($trackedInfo);

if ($db->UpdateOrAddTrackedInfo($trackedInfo) == "UPDATED")
{
    echo $trackedInfo->GetLastSeen()->diff($oldLastSeen)->format();
    if ($trackedInfo->GetLastSeen()->diff($oldLastSeen) > date_create_from_format("i","1")){
        echo "wuhu";
    }
    else
    {
        echo "lol";
    }
}