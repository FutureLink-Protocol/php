<?php
namespace FLP;

class Pair extends Feeder
{
    public $futurelink;
    public $pastlink;

    private $futureLinkRaw;
    private $pastLinkRaw;

    public function __construct(&$pastlink, &$futurelink)
    {
        $this->futureLinkRaw = $futurelink;
        $this->pastLinkRaw = $pastlink;

        $this->futurelink =& MetadataAssembler::fromJSONToMetaData($futurelink);
        $this->pastlink =& MetadataAssembler::fromJSONToMetaData($pastlink);
    }

    public function raw()
    {
        return $this->pastLinkRaw . $this->futureLinkRaw;
    }
}