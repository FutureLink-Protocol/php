<?php
namespace FLP;

class Pair
{
    public $futurelink;
    public $pastlink;

    private $futureLinkRaw;
    private $pastLinkRaw;

    public function __construct(&$pastlink, &$futurelink)
    {
        $this->futureLinkRaw = $futurelink;
        $this->pastLinkRaw = $pastlink;

        $this->futurelink =& MetadataAssembler::fromRawToMetaData($futurelink);
        $this->pastlink =& MetadataAssembler::fromRawToMetaData($pastlink);
    }

    public function raw()
    {
        return $this->pastLinkRaw . $this->futureLinkRaw;
    }
}