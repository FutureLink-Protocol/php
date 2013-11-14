<?php
namespace FLP;
// Programmer: Robert Plummer
//
// Purpose: Adds PastLink UI to page.  Makes it so that sentences wrapped in a PastLink are distinguished from the rest of the text in a page for the end user

Class PastLink extends Feed
{
	public $type = 'futurelink';
	public $version = 0.1;
	public $isFileGal = false;
	public $debug = false;
	public $page = '';
	public $metadata = array();

	static $pairs;
	static $addedHashes;

	function __construct($page = "", $data = "")
	{
		$this->page = $page;

		if (!empty($page) && !empty($data)) {
			$this->metadata = MetadataAssembler::pastLink($page, $data);
		}

		return parent::__construct($page);
	}

	static function add($clipboarddata, $page, $data)
	{
		$me = new PastLink($page, $data);

		$item = new Pair( $me->metadata->raw, $clipboarddata );

		if (isset(self::$addedHashes[$item->pastlink->hash])) {
            return null;
        }

		self::$addedHashes[$item->pastlink->hash] = true;
		$item->futurelink->href = str_replace(' ', '+', $item->futurelink->href);

        PastLink::$pairs->add($item);

		return PastLink::$pairs->length;
	}

	static function clearAll()
	{
        PastLink::$pairs = new Pairs();
	}

	public function getContents()
	{
		if (PastLink::$pairs->length > 0) {
			$this->setEncoding(PastLink::$pairs->raw());

			return PastLink::$pairs;
		}

		return array();
	}
}

//define pairs
PastUI::$pairs = new Pairs();