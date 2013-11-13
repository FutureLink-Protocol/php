<?php
namespace FutureLink;

class Pair
{
    public $futurelink;
    public $pastlink;

    public function __construct(&$pastlink, &$futurelink)
    {
        $this->futurelink =& MetadataAssembler::fromRawToMetaData($futurelink);
        $this->pastlink =& MetadataAssembler::fromRawToMetaData($pastlink);
    }
}