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
    public $to;

    /**
     * @param $body
     */
    public function __construct($body)
	{
		$this->parser = new Phraser\Phraser();
		$this->body = new Phraser\Phrase($body);
	}

    public function setContextAsPast()
    {
        $this->to = 'future';
        $this->parser->cssClassBeginning = 'futurelink-beginning';
        $this->parser->cssClassMiddle = 'futurelink';
        $this->parser->cssClassEnd = 'futurelink-end';
    }

    public function setContextAsFuture()
    {
        $this->to = 'past';
        $this->parser->cssClassBeginning = 'pastlink-beginning';
        $this->parser->cssClassMiddle = 'pastlink';
        $this->parser->cssClassEnd = 'pastlink-end';
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