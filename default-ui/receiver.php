<?php
require_once("../autoload.php");
require_once "rb.php";

$debug = true;

FLP\Events::bind(new FLP\Event\RevisionLookup(function(Phraser\Phrase $text, &$exists, FLP\Revision &$revision) {
	$found = R::findAll('article', ' sanitized LIKE ? ', array( '%' . $text->sanitized . '%'));
	if ($found) {
		$exists = true;
		$revision->data = $text->sanitized;
	}
}));

FLP\Events::bind(new FLP\Event\FilterPreviouslyVerified(function(FLP\Pair &$pair, &$exists) {
	$exists = false;
}));

FLP\Events::bind(new FLP\Event\Accepted(function(FLP\Pair &$pair) {
	$test = '';
}));

ob_start();

if (isset($_POST['protocol']) && $_POST['protocol'] == 'futurelink' && isset($_POST['metadata'])) {

	//here we do the confirmation that another wiki is trying to talk with this one
	$metadata = json_decode($_POST['metadata']);

	foreach($metadata->feed->items as $item) {
		$pair = new FLP\Pair($item->past, $item->future);

		$pair->origin = (isset($_POST['REMOTE_ADDR']) ? $_POST['REMOTE_ADDR'] : '');
		$response = new FLP\Response();
		$pairReceived = new FLP\PairReceived();

		if ($pairReceived->addItem($pair) == true) {
			$response->response = 'success';
		} else {
			$response->response = 'failure';
		}

		$feed = $response->feed($_SERVER['REQUEST_URI']);

		if (
			$response->response == 'failure'
		) {
			$feed->reason = $pairReceived->security->verifications;
		}

		if ($debug) {
			$feed->debug = ob_get_clean();
		}
		echo json_encode($feed);
	}

	if (!isset($_POST['continue'])) {
		exit();
	}
}