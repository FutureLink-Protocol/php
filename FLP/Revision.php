<?php
namespace FLP;

/**
 * Class Revision
 * @package FLP
 */
class Revision
{
    public $name;
    public $version;
    public $data;
    public $date;
    public $phrase;

    /**
     * @param String [$name]
     * @param String [$version]
     * @param array [$data]
     * @param Integer [$date]
     * @param String [$phrase]
     */
    public function __construct($name = null, $version = null, $data = null, $date = null, $phrase = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->data = $data;
        $this->date = $date;
        $this->phrase = $phrase;
    }
} 