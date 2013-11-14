<?php
namespace FutureLink;

Class ReceiveFromPast extends Feed
{
	var $type = "futurelink";
	var $isFileGal = false;
	var $version = 0.1;
	var $showFailures = false;
	var $response = 'failure';

	static function wikiView($args)
	{
        //TODO: abstract
		if (isset($_POST['protocol']) && $_POST['protocol'] == 'futurelink' && isset($_POST['metadata'])) {
			$me = new self($args['object']);
			$futureLink = new FutureUI($args['object']);

			//here we do the confirmation that another wiki is trying to talk with this one
			$metadata = json_decode($_POST['metadata']);
			$metadata->origin = $_POST['REMOTE_ADDR'];

			if ($futureLink->addItem($metadata) == true) {
				$me->response = 'success';
			} else {
				$me->response = 'failure';
			}

			$feed = $me->feed($_SERVER['REQUEST_URI']);

			if (
				$me->response == 'failure' &&
				$futureLink == true
			) {
				$feed->reason = $futureLink->verifications;
			}

			echo json_encode($feed);
			exit();
		}
	}

	public function getContents()
	{
		$this->setEncoding($this->response);
		return $this->response;
	}
}