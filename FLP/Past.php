<?php
namespace FLP;

/**
 * Class Past
 * @package FLP
 */
Class Past
{
	public $version = 0.1;
	public $debug = false;
	public $metadata;

    /**
     * @param string $data
     */
    function __construct($data = "")
	{
		if (!empty($name) && !empty($data)) {
			$this->metadata = MetadataAssembler::past($data);
		}

	}
}