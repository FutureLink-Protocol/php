<?php
namespace FLP\Event;

/**
 * Class Send
 * @package FLP\Event
 */
class Send extends Base
{
    /**
     * @param String $url
     * @param array $params
     * @param $result
     * @param $item
     * @param $items
     */
    public function trigger($url, $params, &$result, &$item, &$items)
    {
        foreach($this->delegates as &$delegate)
        {
            $delegate($url, $params, $result, $item, $items);
        }
    }
}