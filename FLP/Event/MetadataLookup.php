<?php

namespace FLP\Event;


class MetadataLookup extends Base
{
    public function trigger($objectName, $item, &$value)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($objectName, $item, $value);
        }
    }
} 