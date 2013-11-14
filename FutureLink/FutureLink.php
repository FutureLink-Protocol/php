<?php
namespace FutureLink;
// Programmer: Robert Plummer
//
// Purpose: Inject FutureLink UI components into Wiki editing screens.  Managed page's saved attributes per
//          FutureLink UI interaction.  Generates and presents FutureLink text string to user.

use Phraser;

Class FutureLink extends File
{
	var $type = 'futurelink';
	var $version = 0.1;
	var $debug = false;
	static $pagesParsed = array();
	static $parsedDatas = array();
	var $metadata = array();
	public $security;
	var $itemsAdded = array();

	function __construct($name)
	{
		$this->security = new Security();
        $this->name = $name;
		$this->metadata = MetadataAssembler::futureLink($name);
		return parent::__construct($name);
	}

	static function save($page, $body, $version)
	{
		$body = Phraser\Parser::superSanitize($body);
        Events::triggerCreateRevision($page, $body, $version);
	}

	function addItem($item)
	{
		parent::addItem($item);

		$exists = array();
        $existsCount = 0;
		$verificationsCount = $this->security->verificationsCount;
		foreach ($this->verifications as &$verification) {
			foreach ($verification['reason'] as $reason) {
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
