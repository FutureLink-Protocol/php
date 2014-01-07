<?php
namespace FLP;

use Exception;

/**
 * Class Feeder
 * @package FLP
 */
abstract class Feeder
{
	public $contents = null;
	public $contentsRaw;
	public $version = "0.1";
	public $encoding = "";
	public $response;

    /**
     *
     */
    function __construct()
	{
		$this->open();
		if (!isset($this->contents)) {
			$this->contents = new Contents();
			//at this point contents is empty, so lets fill it
			$this->replace();
		}
	}

    /**
     *
     */
    public function replace()
	{

	}

    /**
     * @throws \Exception
     */
    public function setEncoding()
	{
		if (is_array($this->contentsRaw)) throw new Exception('die');
		$this->encoding = mb_detect_encoding($this->contentsRaw, "ASCII, UTF-8, ISO-8859-1");
	}

    /**
     *
     */
    private function open()
	{
		Events::triggerFeedLookup($this->contentsRaw);

		$this->setEncoding();

		$this->contents = json_decode($this->contentsRaw);
	}

    /**
     * @return $this
     */
    private function save()
	{
		$contents = json_encode($this->contents);

		Events::triggerFeedSave($this->name, $contents);

		return $this;
	}

    /**
     * @param $items
     */
    function appendToContents($items)
	{
		if (isset($items->feed->entry)) {
			$this->contents->items[] = $items->feed->item;
		} elseif (isset($items)) {
			$this->contents->items[] = $items;
		}
	}

    /**
     * @param $item
     * @return $this
     */
    public function addItem($item)
	{
		$this->appendToContents($item);

		$this->save();

		return $this;
	}

    /**
     * @param $origin
     * @return Feed
     */
    public function feed($origin)
	{
		$feed = new Feed(
			$this->version,
			$this->encoding,
			(isset($this->contents) ? $this->contents : new Contents()),
            $origin,
			$this->response
		);

		return $feed;
	}
}
