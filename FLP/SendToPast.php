<?php
namespace FLP;

use Exception;

/**
 * Class SendToPast
 * @package FLP
 */

class SendToPast
{
	public static $version = 0.1;

    /**
     * @return array|null
     * @throws Exception
     */
    public static function send()
	{
		$sent = array();
        $result = array();

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
                    $communicator = new Communicator($pair->past->href, array(
                        'protocol'=> 'futurelink',
                        'metadata'=> $feedJson,
                        'continue'=> 'true'
                    ));

                    $resultJson = null;

                    if (!empty($communicator->result)) {
                        $result = $communicator->result;
                        $resultJson = json_decode($result);
                    }

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
