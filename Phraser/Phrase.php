<?php
namespace Phraser;

class Phrase
{
    public $original;
    public $sanitized;
	public $words;

    public function __construct($text)
    {
        $this->original = $text;
	    $this->words = Phraser::sanitizeToWords($text);
        $this->sanitized = implode('', $this->words);
    }
}