<?php

require ("../../psf.php");
require ("../../includes/unit_test/ut.php");

$ut = new UnitTest();

$ut->Evaluate("xx", false);
$ut->Evaluate("x2", true);

echo ("\n\n");

$ut->PrintResults();
$ut->ExitWithErrorCount();
