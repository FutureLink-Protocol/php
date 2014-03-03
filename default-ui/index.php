<?php
require_once("../vendor/autoload.php");

if (isset($_REQUEST['reset'])) {
	FLP\Data::wipe();
} else {
	echo FLP\Service\Receiver::receive();
	if (!isset($_POST['continue'])) {
		exit();
	}
}