<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 11/13/13
 * Time: 1:38 PM
 */

namespace FLP\Event;

/**
 * Class MetadataSet
 * @package FLP\Event
 */
class MetadataSet extends Base
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