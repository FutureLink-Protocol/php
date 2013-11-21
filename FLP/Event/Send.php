<?php
namespace FLP\Event;

class Send extends Base
{
    public function trigger($url, $params, &$result, &$item, &$items)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($url, $params, $result, $item, $items);
        }
    }
}