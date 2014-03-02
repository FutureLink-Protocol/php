<?php
namespace FLP;

use Phraser;

class PairAssembler
{
    public $pair;
    public $pastText;
    public $futureText;
    public static $count = array();

    public function __construct($raw)
    {
        $json = json_decode($raw);

        $this->pair = new Pair($json->past, $json->future);

        $this->pastText = new Phraser\Phrase($this->pair->past->text);
        $this->futureText = new Phraser\Phrase($this->pair->future->text);

        if (!isset(self::$count[$this->pair->past->text])) {
            self::$count[$this->pair->past->text] = 1;
        } else {
            self::$count[$this->pair->past->text]++;
        }
    }
}