<?php
namespace FLP;

class Pair extends Feeder
{
    public $future;
    public $past;

    private $futureRaw;
    private $pastRaw;

    public function __construct(&$past, &$future)
    {
        $this->futureRaw = $future;
        $this->pastRaw = $past;

        $this->future =& MetadataAssembler::fromJSONToMetaData($future);
        $this->past =& MetadataAssembler::fromJSONToMetaData($past);
    }

    public function raw()
    {
        return $this->pastRaw . $this->futureRaw;
    }
}