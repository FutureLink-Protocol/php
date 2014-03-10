<?php
require_once("../vendor/autoload.php");
$_POST['continue'] = false;
$debug = true;

FLP\Events::bind(new FLP\Event\Send(function($url, $params, &$result, &$item, &$items) use ($debug) {
	if ($_POST['continue']) {
		foreach($params as $key => $param) {
			$_POST[$key] = $param;
		}
		FLP\Service\Receiver::receive();
		$result = ob_get_clean();
		print_r($result);
	} else {
        $communicator = new FLP\Communicator($url, $params);

		if ($debug) {
			if (($json = $communicator->json()) == null) {
				echo str_replace('\/', '/', $communicator->result);
			} else {
				echo $json->debug;
			}
		}
	}
}));

FLP\SendToPast::send("http://localhost/p/flp-php/index.php");