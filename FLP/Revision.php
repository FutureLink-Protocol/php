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
    public $sanitized;

    /**
     * @param String [$title]
     * @param String [$version]
     * @param array [$data]
     * @param String [$sanitized]
     */
    public function __construct($title = null, $version = null, $data = null, $sanitized = null)
    {
        $this->title = $title;
        $this->version = $version;
        $this->data = $data;
        $this->sanitized = $sanitized;
    }
} 