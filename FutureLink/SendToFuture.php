<?php
namespace FutureLink;

use Exception;
// Purpose: Send a pastlink to a futurelink

class SendToFuture extends Feed
{
	public $type = 'futurelink';
    public $version = 0.1;

	static function sendAll()
	{
		$me = new self();
		return($me->send());
	}

	public static function send()
	{
		$me = new self("global");
		$sent = array();
		$pastlink = new PastUI();
		$feed = $pastlink->feed();
        $result = '';

		$items = array();
		//we send something only if we have something to send
		if (empty($feed->feed->entry) == false) {
			foreach ($feed->feed->entry as &$item) {
				if (empty($item->futurelink->href) || isset($sent[$item->futurelink->hash])) continue;
				$sent[$item->futurelink->hash] = true;

				if (!empty($feed->feed->entry)) {
                    try {
                        Events::triggerSend($item->futurelink->href, array(
                            'protocol'=> 'futurelink',
                            'metadata'=> json_encode($feed)
                        ), $result, $item, $items);

                        $resultJson = json_decode($result);

			            //Here we add the date last updated so that we don't have to send it if not needed, saving load time.
			            if (!empty($resultJson->feed) && $resultJson->feed == "success") {
                            $me->addItem(
                                array(
                                   'dateLastUpdated'=> $item->pastlink->dateLastUpdated,
                                   'pastlinklinkHash'=> $item->pastlink->hash,
                                   'futurelinkHash'=> $item->futurelink->hash
					            )
                            );
			            }
			            $items[$item->pastlink->text] = $result;

                    } catch(Exception $e) {
                    }
				}
			}

			return $items;
		}
	}
}
