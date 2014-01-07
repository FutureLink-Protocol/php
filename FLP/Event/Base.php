<?php
namespace FLP\Event;

/**
 * Class Base
 * @package FLP\Event
 */
class Base
{
    /**
     * @var array(function)
     */
    public $delegates = array();

    /**
     * @param function [$delegate]
     */
    public function __construct($delegate = null)
	{
		if ($delegate != null)
		{
			$this->delegates[] =& $delegate;
		}
	}

    /**
     * @param function [$delegate]
     */
    public function bind($delegate)
	{
		$this->delegates[] =& $delegate;
	}

    /**
     * @param mixed $object
     */
    public function trigger(&$object)
	{
		foreach($this->delegates as &$delegate)
		{
			$delegate($object);
		}
	}
} 