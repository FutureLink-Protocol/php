<?php
namespace FLP;

class File
{
    public $dir = 'cache';
    public $name;
    public $file;
    public $encoding;
    public $data;

    public function __construct($name)
    {
        $this->name = $name;
        $this->data = $this->getData();
    }

    public function path()
    {
        return $this->dir . '//' . $this->name;
    }

    private function exists()
    {
        return file_exists($this->path());
    }

    private function getData()
    {
        if ($this->exists()) {
            return file_get_contents($this->path());
        } else {
            return null;
        }
    }

    public function replace($data)
    {
        if ($this->exists()) {
            file_put_contents($this->path() . time(), $data);
        }

        file_put_contents($this->path(), $data);
    }

    public function setEncoding()
    {
        $this->encoding = mb_detect_encoding($this->data, "ASCII, UTF-8, ISO-8859-1");
    }
} 