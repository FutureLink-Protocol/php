<?php
namespace FLP;

use Phraser;
use FLP\Event;

/**
 * Class PairReceived
 * @package FLP
 */
Class PairReceived
{
	/**
	 * @var Security
	 */
	public $security;
    public $revision;

    /**
     *
     */
    function __construct()
	{
		$this->security = new Security();
	}

    /**
     * @param $name
     * @param $body
     * @param $version
     */
    static function save($name, $body, $version)
	{
		$body = Phraser\Parser::superSanitize($body);
        Events::triggerCreateRevision($name, $body, $version);
	}

    /**
     * @param Pair $pair
     * @return bool
     */
    function addItem(Pair $pair)
	{
		$revision = $this->revision = $pair->revision();
		$alreadyAddedPair = Data::getPair($pair);

		//don't let it be added if it already exists
		if ($alreadyAddedPair != null) {
			return false;
		}

		//add if if it doesn't exist
		else if ($revision != null) {
			$this->security->verify($pair, $revision);
	        $existsCount = 0;
			$verificationsCount = $this->security->verificationsCount;
			foreach ($this->security->verifications as &$verification) {
				if ($verification->exists) {
					$existsCount++;
				} else {
					foreach ($verification->reason as $reason) {
						if ($reason == 'exists') {
		                    $existsCount++;
						}
					}
				}
	        }
	        //If they were not added, but the reason is that they already exist, we show that they were sent successfully
			if ($existsCount == $verificationsCount) {
	            return true;
	        }
		}

		//if the phrase or addedPair don't exists, send null
		return null;
	}
}
