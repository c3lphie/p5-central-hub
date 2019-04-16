<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/TrackedInfo.php";

$db = new Database();

$trackedInfo = new TrackedInfo($mac, $macTarget, $signal, new DateTime("now"));

$db->UpdateOrAddTrackedInfo($trackedInfo);