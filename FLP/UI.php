<?php
namespace FLP;

use Phraser;

class UI
{
	public $parser;
	public $body;
	public $phrases = array();
	public function __construct($body)
	{
		$this->parser = new Phraser\Parser();
		$this->body = new Phraser\Phrase($body);
	}

	public function render()
	{
		return $this->parser->parse($this->body->original)->text;
	}
}