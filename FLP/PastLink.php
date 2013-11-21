<?php
namespace FLP;
// Programmer: Robert Plummer
//
// Purpose: Adds PastLink UI to page.  Makes it so that sentences wrapped in a PastLink are distinguished from the rest of the text in a page for the end user

Class PastLink
{
	public $version = 0.1;
	public $debug = false;
	public $metadata;

	function __construct($data = "")
	{
		if (!empty($name) && !empty($data)) {
			$this->metadata = MetadataAssembler::pastLink($data);
		}

	}
}