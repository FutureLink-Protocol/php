<?php

namespace FutureLink\Event;


class MetaLookup extends Base
{
    public function trigger($objectName, $item, &$value)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($objectName, $item, $value);
        }
    }
} 