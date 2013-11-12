<?php
namespace FutureLink;

class File
{
    public $dir = 'cache';
    public $name;
    public $file;

    public function __construct($name)
    {
        $this->name = $name;

    }

    public function path()
    {
        return $this->dir . '//' . $this->name;
    }

    public function delete()
    {
        unlink($this->path());
    }

    public function data()
    {
        return file_get_contents($this->path());
    }

    public function replace($data)
    {
        file_put_contents($this->path(), $data);
    }
} 