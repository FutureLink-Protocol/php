<?php
namespace FLP;

class Pairs extends Feeder
{
    public static $length = 0;
	public static $pairs = array();
	public static $addedHashes;

    public static function raw()
    {
        $raw = '';
        foreach(self::$pairs as $pair)
        {
            $raw .= $pair->raw();
        }
        return $raw;
    }

	static function add(Pair &$pair)
	{
		Pairs::$pairs[] = $pair;
		Pairs::$length++;

		if (isset(Pairs::$addedHashes[$pair->pastlink->hash])) {
			return null;
		}

		self::$addedHashes[$pair->pastlink->hash] = true;
		$pair->futurelink->href = str_replace(' ', '+', $pair->futurelink->href);

		Pairs::$pairs[] = $pair;

		Pairs::$length++;

		return Pairs::$length;
	}

	public function feed($origin)
	{
		$this->contents = new Contents();
		foreach (Pairs::$pairs as $pair) {
			$this->contents->items[] = $pair;
		}

		return parent::feed($origin);
	}
}