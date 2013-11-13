<?php
namespace FutureLink\Event;

class Send extends Base
{
    public function trigger($url, $params, &$item, &$items)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($url, $params, $item, $items);
        }
    }
}