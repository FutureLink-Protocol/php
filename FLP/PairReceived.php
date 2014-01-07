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
		$this->revision = new Revision();
		$exists = false;
		Events::triggerRevisionLookup(new Phraser\Phrase($pair->past->text), $exists, $this->revision);

		if ($exists) {
			$this->security->verify($pair, $this->revision);
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
		return false;
	}
}
