<?php
namespace FLP;

/**
 * Class Revision
 * @package FLP
 */
class Revision
{
    public $title;
    public $version;
    public $data;
    public $date;
    public $phrase;

    /**
     * @param String [$title]
     * @param String [$version]
     * @param array [$data]
     * @param Integer [$date]
     * @param String [$phrase]
     */
    public function __construct($title = null, $version = null, $data = null, $date = null, $phrase = null)
    {
        $this->title = $title;
        $this->version = $version;
        $this->data = $data;
        $this->date = $date;
        $this->phrase = $phrase;
    }
} 