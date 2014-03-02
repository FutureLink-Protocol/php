<?php
require_once "Testify/lib/Testify/Testify.php";
include "vendor/autoload.php";



$tf = new \Testify\Testify("FutureLink-Protocol");

//Test Expressions
$tf->test("Communication", function($tf) {
	(new FLP\Test\Misc("Communication"))->run($tf);
});

//Test Security
$tf->test("Security", function($tf) {
	(new FLP\Test\Misc("Security"))->run($tf);
});

ob_start();
$tf();
$testOutput = ob_get_contents();
file_put_contents("test.html", $testOutput);