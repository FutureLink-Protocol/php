<?php
namespace FLP;

/**
 * Class Pair
 * @package FLP
 */
class Pair extends Feeder
{
    public $future;
    public $past;

    private $futureRaw;
    private $pastRaw;

    /**
     * @param $past
     * @param $future
     */
    public function __construct(&$past, &$future)
    {
        $this->futureRaw = $future;
        $this->pastRaw = $past;
        $this->past = $past;

        $this->future =& MetadataAssembler::fromRawToMetaData($future);
        $this->future =& MetadataAssembler::fromRawToMetaData($past);
        //$this->future =& MetadataAssembler::fromJSONToMetaData($future);
        //$this->past =& MetadataAssembler::fromJSONToMetaData($past);
    }

    /**
     * @return string
     */
    public function raw()
    {
        return $this->pastRaw . $this->futureRaw;
    }
}