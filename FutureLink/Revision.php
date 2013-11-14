<?php
namespace FutureLink;


class Revision
{
    public $name;
    public $version;
    public $data;
    public $date;
    public $phrase;

    public function __construct($name, $version, $data, $date, $phrase)
    {
        $this->name = $name;
        $this->version = $version;
        $this->data = $data;
        $this->date = $date;
        $this->phrase = $phrase;
    }
} 