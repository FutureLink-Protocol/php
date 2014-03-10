<?php
namespace FLP;

use Phraser;

/**
 * Class UI
 * @package FLP
 */
class UI
{
	public $parser;
	public $body;
    public $phraseIndex = -1;

    /**
     * @param $body
     */
    public function __construct($body)
	{
		$this->parser = new Phraser\Phraser();
		$this->body = new Phraser\Phrase($body);
	}

    /**
     * @param Phraser\Phrase $text
     */
    public function addPhrase(Phraser\Phrase $text)
    {
        $added = $this->parser->addIndexes($this->body->words, $text->words);

        if ($added) {
            $this->phraseIndex++;
        }
    }

    /**
     * @return mixed
     */
    public function render()
	{
		return $this->parser->parse($this->body->original)->text;
	}
}