<?php

namespace FLP\Service;

use FLP;

class Receiver
{
	public static function receive() {
		$debug = true;

		ob_start();

		if (isset($_POST['protocol']) && $_POST['protocol'] == 'futurelink' && isset($_POST['metadata'])) {

			//here we do the confirmation that another wiki is trying to talk with this one
			$metadata = json_decode($_POST['metadata']);

			foreach($metadata->feed->items as $item) {
				$pair = new FLP\Pair($item->past, $item->future);

				$pair->origin = (isset($_POST['REMOTE_ADDR']) ? $_POST['REMOTE_ADDR'] : '');
				$response = new FLP\Response();
				$pairReceived = new FLP\PairReceived();

				//addItem = false if the item exists in the system, but isn't new, it is true, when it is new, and it is null in any other case
				$added = $pairReceived->addItem($pair);

				//new
				if ($added == true) {
					$response->response = 'success';
				}

				//already exists
				else if ($added == false) {
					$response->response = 'exists';
				}

				//doesn't exist
				else {
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
				return json_encode($feed);
			}

			return null;
		}
	}
}