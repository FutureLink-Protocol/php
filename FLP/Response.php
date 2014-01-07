<?php
namespace FLP;

/**
 * Class Response
 * @package FLP
 */
Class Response extends Feeder
{
	public $name = "futurelink";
    public $version = 0.1;
    public $showFailures = false;
    public $response = 'failure';
	public $reason;

    /**
     * @return string
     */
    public function getContents()
	{
		$this->setEncoding($this->response);
		return $this->response;
	}
}
