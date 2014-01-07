<?php
namespace FLP;

/**
 * Class Pairs
 * @package FLP
 */
class Pairs extends Feeder
{
    public static $length = 0;
	public static $pairs = array();
	public static $addedHashes;

    /**
     * @return string
     */
    public static function raw()
    {
        $raw = '';
        foreach(self::$pairs as $pair)
        {
            $raw .= $pair->raw();
        }
        return $raw;
    }

    /**
     * @param Pair $pair
     * @return int|null
     */
    static function add(Pair &$pair)
	{
		Pairs::$pairs[] = $pair;
		Pairs::$length++;

		if (isset(Pairs::$addedHashes[$pair->past->hash])) {
			return null;
		}

		self::$addedHashes[$pair->past->hash] = true;
		$pair->future->href = str_replace(' ', '+', $pair->future->href);

		Pairs::$pairs[] = $pair;

		Pairs::$length++;

		return Pairs::$length;
	}

    /**
     * @param $origin
     * @return Feed
     */
    public function feed($origin)
	{
		$this->contents = new Contents();
		foreach (Pairs::$pairs as $pair) {
			$this->contents->items[] = $pair;
		}

		return parent::feed($origin);
	}
}