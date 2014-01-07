<?php
require_once("../autoload.php");
$_POST['continue'] = false;
$debug = true;

FLP\Events::bind(new FLP\Event\Send(function($url, $params, &$result, &$item, &$items) use ($debug) {
	if ($_POST['continue']) {
		foreach($params as $key => $param) {
			$_POST[$key] = $param;
		}

		require_once 'receiver.php';
		$result = ob_get_clean();
		print_r($result);
	} else {
        $communicator = new FLP\Communicator($url, $params);
        $result = $communicator->result;
        $json = json_decode($result);
        if ($json != null) {
            $json->info = $communicator->info;
        }

		if ($debug) {
			if ($json == null) {
				echo str_replace('\/', '/', $result);
			} else {
				echo $json->debug;
			}
		}
	}
}));

FLP\SendToPast::send("http://localhost/p/flp-php/index.php");