<?php
namespace FLP;

Class ReceiveFromPast extends Feed
{
	public $type = "futurelink";
    public $isFileGal = false;
    public $version = 0.1;
    public $showFailures = false;
    public $response = 'failure';


	public function getContents()
	{
		$this->setEncoding($this->response);
		return $this->response;
	}
}
