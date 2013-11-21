<?php
namespace FLP;
// Programmer: Robert Plummer
//
// Purpose: Inject FutureLink UI components into Wiki editing screens.  Managed page's saved attributes per
//          FutureLink UI interaction.  Generates and presents FutureLink text string to user.

use Phraser;

Class FutureLink
{
	public $debug = false;
	public $metadata = array();
	public $security;
	public $itemsAdded = array();

	function __construct($name)
	{
		$this->security = new Security();
        $this->name = $name;
		$this->metadata = MetadataAssembler::futureLink($name);
	}

	static function save($name, $body, $version)
	{
		$body = Phraser\Parser::superSanitize($body);
        Events::triggerCreateRevision($name, $body, $version);
	}

	function addItem($item)
	{
		//$this->file->replace($item);

		$exists = array();
        $existsCount = 0;
		$verificationsCount = $this->security->verificationsCount;
		foreach ($this->security->verifications as &$verification) {
			foreach ($verification->reason as $reason) {
				if ($reason == 'exists') {
					$exists[] = true;
                    $existsCount++;
				}
			}
        }
        //If they were not added, but the reason is that they already exist, we show that they were sent successfully
		if ($existsCount == $verificationsCount) {
            return true;
        }

		return $this->itemsAdded;
	}
}
