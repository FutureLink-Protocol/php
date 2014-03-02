<?php
require_once("../vendor/autoload.php");
require_once("receiver.php");

if (isset($_REQUEST['reset'])) {
	FLP\Data::wipe();
}