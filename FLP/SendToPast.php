<?php
namespace FLP;

use Exception;
// Purpose: Send a past metadata to a future

class SendToPast
{
	public static $version = 0.1;

	public static function send()
	{
		$sent = array();
        $result = '';

		$items = array();
		//we send something only if we have something to send
		if (empty(Pairs::$pairs) == false) {
			foreach (Pairs::$pairs as &$pair) {
				if (empty($pair->past->href) || isset($sent[$pair->past->hash])) {
					continue;
				}

				$sent[$pair->past->hash] = true;
                try {
	                $feed = $pair->feed($_SERVER['REQUEST_URI']);
	                $feed->feed->items[] = $pair;
	                $feedJson = json_encode($feed);
                    Events::triggerSend($pair->past->href, array(
                        'protocol'=> 'futurelink',
                        'metadata'=> $feedJson,
	                    'continue'=> 'true'
                    ), $result, $pair, Pairs::$pairs);


                    $resultJson = json_decode($result);

		            //Here we add the date last updated so that we don't have to send it if not needed, saving load time.
		            if (!empty($resultJson->feed) && $resultJson->feed == "success") {
			            Events::triggerSuccess($pair, Pairs::$pairs);
		            }
		            $items[$pair->past->text] = $result;

                } catch(Exception $e) {
                    Throw new $e;
                }
			}

			return $items;
		}

		return null;
	}
}
