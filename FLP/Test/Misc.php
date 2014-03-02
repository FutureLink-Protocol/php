<?php
namespace FLP\Test;

use Testify\Testify;

class Misc
{
	public $directoryName;
	public $directory;
	public $files;
	public $parser;

	public function __construct($directoryName)
	{
		global $unitTestsRunning;
		$unitTestsRunning = true;
		$this->directoryName = $directoryName;
		$this->directory = dirname(__FILE__) . '/' . $directoryName;
		$this->files = scandir($this->directory);
	}

	public function run(Testify &$testify)
	{
		foreach($this->files as $file) {
			if($file === '.' || $file === '..') {continue;}
			$name = substr($file, 0, -4);
			$class = "FLP\\Test\\" . $this->directoryName . "\\" . $name;
			$test = new $class($this->parser);

			$testify->assertEquals($test->actual, $test->expected, "Testing: " . $class);
		}
	}
} 