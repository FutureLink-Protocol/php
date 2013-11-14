<?php
namespace FutureLink;

class Pairs
{
    public $entry = array();
    public $length = 0;

    public function add(Pair $metadata)
    {
        $this->entry[] = $metadata;
        $this->length++;
    }

    public function raw()
    {
        $raw = '';
        foreach($this->entry as $pair)
        {
            $raw .= $pair->raw();
        }
        return $raw;
    }
}