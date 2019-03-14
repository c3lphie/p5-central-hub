<?php
declare(strict_types=1);
require_once __DIR__ . "/../lib/MacUtils.php";

MacUtils::Validate("AA:BB:CC:DD:EE:FF");

MacUtils::Validate("AA:BB:CC:DD:EE:GG");

MacUtils::Validate(2);
