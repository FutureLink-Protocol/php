<?php
include "vendor/autoload.php";

$tf = new \Testify\Testify("FutureLink-Protocol");

//Test Expressions
$tf->test("Communication", function($tf) {
	(new FLP\Test\Misc("Communication"))->run($tf);
});

//Test Security
/*$tf->test("Security", function($tf) {
	(new FLP\Test\Misc("Security"))->run($tf);
});*/

//Test Phraser
$tf->test("Phraser", function($tf) {
	(new FLP\Test\Misc("Phraser"))->run($tf);
});

ob_start();
$tf();
$testOutput = ob_get_contents();