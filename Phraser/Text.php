<?php
namespace Phraser;

class Phrase
{
    public $original;
    public $sanitized;

    public function __construct($text)
    {
        $this->original = $text;
        $this->sanitized = Parser::superSanitize($text);
    }
}