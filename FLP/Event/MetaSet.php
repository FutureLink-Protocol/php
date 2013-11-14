<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 11/13/13
 * Time: 1:38 PM
 */

namespace FutureLink\Event;


class MetaSet extends Base
{
    public function trigger($objectName, $item, &$value)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($objectName, $item, $value);
        }
    }
} 