<?php
namespace FLP;

Class Response extends Feeder
{
	public $name = "futurelink";
    public $version = 0.1;
    public $showFailures = false;
    public $response = 'failure';
	public $reason;


	public function getContents()
	{
		$this->setEncoding($this->response);
		return $this->response;
	}
}