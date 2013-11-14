<?php
namespace FLP;


class Revision
{
    public $name;
    public $version;
    public $data;
    public $date;
    public $phrase;

    public function __construct($name = null, $version = null, $data = null, $date = null, $phrase = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->data = $data;
        $this->date = $date;
        $this->phrase = $phrase;
    }
} 