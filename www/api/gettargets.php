<?php

require_once __DIR__ . "/../../lib/Database.php";
require_once __DIR__ . "/../../lib/Device.php";

$db = new Database();

$array = array();

/** @var Target $target */
foreach ($db->GetTargets() as $target)
{
    array_push($array, $target->GetArray());
}

echo json_encode($array);