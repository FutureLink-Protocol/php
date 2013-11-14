<?php

namespace FLP;

class Container
{
    public $version;
	public $encoding;
	public $feed;
	public $origin;
	public $type;

    public function __construct($version, $encoding, $feed, $origin, $type)
    {
        $this->version = $version;
        $this->encoding = $encoding;
        $this->feed = $feed;
        $this->origin = $origin;
        $this->type = $type;
    }
}