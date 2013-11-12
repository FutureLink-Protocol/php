<?php
namespace FutureLink;

use Exception;

abstract class Feed
{
	public $name = "";
	public $items = array();
	public $item = array();
	public $contents = array();
	public $type = "";
	public $isFileGal = false;
	public $version = "0.0";
	public $encoding = "";

	function __construct($name = "")
	{
		if (empty($name)) {
			$name = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			$name = explode('/', $name);
			array_pop($name);
			$name = implode($name, '/');
		} else {
			$name = str_replace("http://", "", $name);
			$name = str_replace("https://", "", $name);
			$name = explode('/', $name);
			$name = array_shift($name);
		}

		$this->name = $this->type . "_" . $name;
	}

	public function getItems()
	{
		$contents = $this->getContents();

		if (empty($contents->entry)) return array();

		return $contents->entry;
	}

	public function listItemNames()
	{
		$result = array();
		foreach ($this->getItems() as $item) {
			if (!empty($item->name)) {
				$result[] = addslashes(htmlspecialchars($item->name));
			}
		}
		return $result;
	}

	public function getItem($name)
	{
		foreach ($this->getItems() as $item) {
			if ($name == $item->name) {
				return $item;
			}
		}
		return array();
	}

	public function replace()
	{

	}

	public function setEncoding($contents)
	{
		if (is_array($contents)) throw new Exception('die');
		$this->encoding = mb_detect_encoding($contents, "ASCII, UTF-8, ISO-8859-1");
	}

	private function open()
	{
        $contents = (new File($this->name))->data();

		$this->setEncoding($contents);

		$contents = json_decode($contents);
		if (empty($contents)) return array();
		return $contents;
	}

	private function save($contents)
	{
		$contents = json_encode($contents);

        (new File($this->name))
            ->replace($contents);

		return $this;
	}

	public function getContents()
	{
		$contents = $this->open();

		if (!empty($contents)) return $contents;

		//at this point contents is empty, so lets fill it
		$this->replace();

		$contents = $this->open();

		return $contents;
	}

	public function delete()
	{
        (new File($this->name))->delete();
	}

	function appendToContents(&$contents, $items)
	{
		if (isset($items->feed->entry)) {
			$contents->entry[] = $items->feed->entry;
		} elseif (isset($items)) {
			$contents->entry[] = $items;
		}
	}

	public function addItem($item)
	{
		$contents = $this->open();

		if (empty($contents)) {
			$contents = new Contents($this->type);
		}

		//this allows us to intercept the contents and do things like check the validity of the content being appended to the contents
		$this->appendToContents($contents, $item);

		$this->save($contents);

		return $this;
	}

	public function feed($origin = '')
	{
		global $tikilib;
		$contents = $this->getContents();

        //TODO: convert to actual object
		$feed = new Feed_Container(
			$this->version,
			$this->encoding, //we get this from the above call to open
			$contents,
            (!empty($origin) ? $origin : $tikilib->tikiUrl() . 'tiki-feed.php'),
			$this->type
		);

		return $feed;
	}

	public function listArchives()
	{
		$archives = array();

		if ($this->isFileGal == true) {
			$file = FileGallery_File::filename($this->name);
			foreach ($file->listArchives() as $archive) {
				$archive = $this->open();
				$archives[$archive->feed->date] = $archive->feed->entry;
			}
		}

		return $archives;
	}
}
