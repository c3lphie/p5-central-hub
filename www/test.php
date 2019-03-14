<?php

require_once __DIR__ . "/../lib/MacUtils.php";

echo MacUtils::Validate("AA:BB:CC:DD:EE:FF");

echo MacUtils::Validate("AA:BB:CC:DD:EE:GG");

echo MacUtils::Validate(1);