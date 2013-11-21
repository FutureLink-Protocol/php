<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 11/13/13
 * Time: 1:38 PM
 */

namespace FLP\Event;


class MetadataSet extends Base
{
    public function trigger($objectName, $item, &$value)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($objectName, $item, $value);
        }
    }
} 