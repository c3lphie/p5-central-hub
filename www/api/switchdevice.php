<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/MacUtils.php";

$error = "";


$mac = strtoupper((string)$_GET["mac"]);
$state = (int)$_GET["state"];

if (!isset($_GET["mac"])) $error = $error . "mac, ";
if (!isset($_GET["state"])) $error = $error . "state, ";

if ($error != "")
{
    $error = substr($error, 0, -2);
    $error = $error . " get parameter(s) not set";
    die ('{"error": "' . $error . '"}');
}

$db = new Database();

try {
    if (!MacUtils::Validate($mac)) die ('{"error": "mac is invalid"}');

    if ($state == 1)
    {
        $db->SetState($mac, true);
    }
    elseif ($state == 0)
    {
        $db->SetState($mac, false);
    }
    else
    {
        Error("Error in device switch");
    }
} catch (Exception $e) {
    die('{"error": "Could not create DateTime"}');
}