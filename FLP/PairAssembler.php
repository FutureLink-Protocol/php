<?php
namespace FLP;

use Phraser;

class PairAssembler
{
    public $pair;
    public $pastText;
    public $futureText;
    public static $counts = array();

    public function __construct($raw = '')
    {
	    if ($raw != '')
	    {
	        $json = json_decode($raw);

	        $this->pair = new Pair($json->past, $json->future);

	        $this->pastText = new Phraser\Phrase($this->pair->past->text);
	        $this->futureText = new Phraser\Phrase($this->pair->future->text);

            $this->increment();
	    }
    }

    public function increment()
    {
        if (!isset(self::$counts[$this->pastText->sanitized])) {
            self::$counts[$this->pastText->sanitized] = 1;
        } else {
            self::$counts[$this->pastText->sanitized]++;
        }
    }
}