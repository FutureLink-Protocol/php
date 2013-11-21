<?php
require_once("../autoload.php");
$_POST['continue'] = true;
$debug = true;

FLP\Events::bind(new FLP\Event\Send(function($url, $params, &$result, &$item, &$items) use ($debug) {
	if (isset($_POST['continue'])) {
		foreach($params as $key => $param) {
			$_POST[$key] = $param;
		}

		require_once 'receiver.php';
		$result = ob_get_clean();
		print_r($result);
	} else {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result = curl_exec($ch);

		if ($debug) {
			$json = json_decode($result);
			if ($json == null) {
				echo str_replace('\/', '/', $result);
			} else {
				echo $json->debug;
			}
		}

		$info = curl_getinfo($ch);
		curl_close($ch);
	}
}));

FLP\SendToPast::send("http://localhost/");