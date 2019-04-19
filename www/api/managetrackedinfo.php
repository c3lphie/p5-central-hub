<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/TrackedInfo.php";
require_once __DIR__ . "/../../lib/Device.php";
require_once __DIR__ . "/../../lib/Target.php";
require_once __DIR__ . "/../../lib/MacUtils.php";

$error = "";
$db = new Database();

$trackerMAC = array("DC:4F:22:0A:05:82","5C:CF:7F:69:08:3D","2C:3A:E8:1B:53:75");

foreach ($db->GetDevices() as $device)
{
    if ($device->GetType() == DeviceType::Light)
    {
        $lightDev = $device;
    }
    elseif ($device->GetType() == DeviceType::Lock)
    {
        $lockDev = $device;
    }
}

$targets = array();
foreach ($db->GetTargets() as $target)
{
    array_push($targets, $target);
}

$trackedInfos = array();
foreach ($db->GetTrackedInfo() as $tracked)
{
    array_push($trackedInfos, $tracked);
}

$mac = strtoupper((string)$_GET["mac"]);
$macTarget = strtoupper((string)$_GET["mactarget"]);
$_signal = (string) $_GET["signalstrength"];

if (is_numeric($_signal))
{
    $signal = (int)$_signal;
}
else
{
    Error("signalstrength must be integer");
}


if (!isset($_GET["mac"])) $error = $error . "mac, ";
if (!isset($_GET["mactarget"])) $error = $error . "mactarget, ";
if (!isset($_GET["signalstrength"])) $error = $error . "signalstrength, ";

if ($error != "")
{
    $error = substr($error, 0, -2);
    $error = $error . " get parameter(s) not set";
    die ('{"error": "' . $error . '"}');
}



try {
    if (!MacUtils::Validate($mac)) die ('{"error": "mac is invalid"}');

    if (!MacUtils::Validate($macTarget)) die ('{"error": "mactarget is invalid"}');

    $trackedInfo = new TrackedInfo($mac, $macTarget, $signal, new DateTime("now"));
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}

foreach ($targets as $target)
{
    if ($target->GetMac() == $trackedInfo->GetMacTarget())
    {
        if ($db->UpdateOrAddTrackedInfo($trackedInfo) == "UPDATED")
        {

            if($db->GetSignalStrength($trackerMAC[0],$trackedInfo->GetMacTarget()) < $db->GetSignalStrength($trackerMAC[1],$trackedInfo->GetMacTarget()) && $db->GetSignalStrength($trackerMAC[0],$trackedInfo->GetMacTarget()) < $db->GetSignalStrength($trackerMAC[2],$trackedInfo->GetMacTarget()))
            {
                if ($db->GetDeviceState($lightDev->GetMac()) == 0)
                {
                    $db->SetState($lightDev->GetMac(), true);
                }
                elseif ($db->GetDeviceState($lightDev->GetMac()) == 1)
                {
                    $db->SetState($lightDev->GetMac(), false);
                }
            }
        }
    }
}