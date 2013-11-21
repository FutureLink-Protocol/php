<?php

namespace FLP;

class Contents
{
    public $date = 0;
    public $items = array();

	public function __construct()
	{
		$this->date = time();
	}
}