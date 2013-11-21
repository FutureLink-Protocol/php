<?php
require_once("../autoload.php");
require_once "rb.php";

$debug = true;

ob_start();

FLP\Events::bind(new FLP\Event\RevisionLookup(function(Phraser\Phrase $text, FLP\Revision &$revision) {
	if ($found = R::findOne('article', ' sanitized LIKE ? ', array( '%' . $text->sanitized . '%'))) {
		throw new Exception("We found it!");
	}
}));

if (isset($_POST['protocol']) && $_POST['protocol'] == 'futurelink' && isset($_POST['metadata'])) {

	//here we do the confirmation that another wiki is trying to talk with this one
	$metadata = json_decode($_POST['metadata']);

	foreach($metadata->feed->items as $item) {
		$pair = new FLP\Pair($item->pastlink, $item->futurelink);

		$pair->origin = (isset($_POST['REMOTE_ADDR']) ? $_POST['REMOTE_ADDR'] : '');
		$receive = new FLP\ReceiveFromFuture();
		$futureLink = new FLP\FutureLink('all');

		if ($futureLink->addItem($_POST['metadata']) == true) {
			$receive->response = 'success';
		} else {
			$receive->response = 'failure';
		}

		$feed = $receive->feed($_SERVER['REQUEST_URI']);

		if (
			$receive->response == 'failure' &&
			$futureLink == true
		) {
			$feed->reason = $futureLink->security->verifications;
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