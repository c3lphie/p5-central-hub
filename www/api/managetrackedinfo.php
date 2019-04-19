<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/TrackedInfo.php";
require_once __DIR__ . "/../../lib/Device.php";
require_once __DIR__ . "/../../lib/MacUtils.php";

$error = "";
$db = new Database();

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

$oldLastSeen = $db->GetOldLastSeen($trackedInfo)->getTimestamp();
$newLastSeen = $trackedInfo->GetLastSeen()->getTimestamp();

$db->AddTrackedInfo($trackedInfo);
die();

if ($db->UpdateOrAddTrackedInfo($trackedInfo) == "UPDATED")
{
    if ($newLastSeen - $oldLastSeen < 60)
    {
        /**
         * Run this part if there has gone LESS than a minute
         */
        if ($trackedInfo->GetSignal() < 20 && $db->GetDeviceState($lightDev->GetMac()) == 0)
        {
            $db->SetState($lightDev->GetMac(), true);
        }
        elseif ($trackedInfo->GetSignal() > 20 && $db->GetDeviceState($lightDev->GetMac()) == 1)
        {
            $db->SetState($lightDev->GetMac(), false);
        }

        if ($trackedInfo->GetSignal() < 40 && $db->GetDeviceState($lockDev->GetMac()) == 0)
        {
            $db->SetState($lockDev->GetMac(), true);
        }
        elseif ($trackedInfo->GetSignal() > 40 && $db->GetDeviceState($lockDev->GetMac()) == 1)
        {
            $db->SetState($lockDev->GetMac(), false);
        }


    }
    else
    {
        /**
         * Run this part if there has gone MORE than a minute
         */


    }
}