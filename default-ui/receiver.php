<?php
require_once("../vendor/autoload.php");
require_once "rb.php";

$debug = true;
R::setup();

FLP\Events::bind(new FLP\Event\RevisionLookup(function(Phraser\Phrase $text, &$exists, FLP\Revision &$revision) {
    $found = R::findOne('article', ' sanitized LIKE ? ', array( '%' . $text->sanitized . '%'));
    if ($found) {
        $exists = true;
        $revision->name = $found->title;
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
            if ($foundPair = R::findOne('pair',' title = ? ', array($pairReceived->revision->name))) {
                $response->response = 'exists';
            } else {
                try {
                $articlePair = R::dispense('pair');
                } catch (Exception $e) {
                    echo $e;
                }
                $articlePair->title = $pairReceived->revision->name;
                $pairAsJson = json_encode($pair);
                $articlePair->pair = $pairAsJson;
                R::store($articlePair);
			    $response->response = 'success';
            }
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