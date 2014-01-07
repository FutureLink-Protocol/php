<?php

namespace FLP;

/**
 * Class Feed
 * @package FLP
 */
class Feed
{
    public $version;
	public $encoding;
	public $feed;
	public $origin;
	public $response;

    /**
     * @param $version
     * @param $encoding
     * @param Contents $feed
     * @param $origin
     * @param $response
     */
    public function __construct($version, $encoding, Contents $feed, $origin, $response)
    {
        $this->version = $version;
        $this->encoding = $encoding;
        $this->feed = $feed;
	    $this->origin = $origin;
	    $this->response = $response;
    }
}