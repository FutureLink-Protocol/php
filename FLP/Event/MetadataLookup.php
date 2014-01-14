<?php

namespace FLP\Event;

/**
 * Class MetadataLookup
 * @package FLP\Event
 */
class MetadataLookup extends Base
{
    /**
     * @param $linkType
     * @param $value
     */
    public function trigger($linkType, &$value)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($linkType, $value);
        }
    }
} 