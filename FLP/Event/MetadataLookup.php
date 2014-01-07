<?php

namespace FLP\Event;

/**
 * Class MetadataLookup
 * @package FLP\Event
 */
class MetadataLookup extends Base
{
    /**
     * @param String $objectName
     * @param $item
     * @param $value
     */
    public function trigger($objectName, $item, &$value)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($objectName, $item, $value);
        }
    }
} 