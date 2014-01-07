<?php

namespace FLP;

/**
 * Class Contents
 * @package FLP
 */
class Contents
{
    /**
     * @var int
     */
    public $date = 0;

    /**
     * @var array
     */
    public $items = array();

    /**
     *
     */
    public function __construct()
	{
		$this->date = time();
	}
}