<?php

require_once __DIR__ . "/../../Database.php";
require_once __DIR__ . "/../../TrackedInfo.php";

$db = new Database();

$trackedInfo = new TrackedInfo($mac, $macTarget, $signal, new DateTime("now"));

$db->UpdateOrAddTrackedInfo($trackedInfo);