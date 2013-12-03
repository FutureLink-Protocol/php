<?php
namespace FLP;

use Phraser;

class UI
{
	public $parser;
	public $body;
    public $phraseIndex = -1;
	public function __construct($body)
	{
		$this->parser = new Phraser\Parser();
		$this->body = new Phraser\Phrase($body);
	}

    public function addPhrase(Phraser\Phrase $text)
    {
        $this->parser->addIndexes($this->body->words, $text->words);
        $this->phraseIndex++;
    }

	public function render()
	{
		return $this->parser->parse($this->body->original)->text;
	}
}