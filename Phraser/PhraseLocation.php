<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 11/21/13
 * Time: 3:08 PM
 */

namespace Phraser;


class PhraseLocation
{
	public $start;
	public $end;
	public $phraseWords;
	public $parentWords;
	public $index = 0;
	public $ended = false;

	private static $phraseLocations = array();

	public function __construct($start, $end, $phraseWords, $parentWords)
	{
		$this->start = $start;
		$this->end = $end;

		$this->phraseWords = $phraseWords;
		$this->parentWords = $parentWords;

		$this->index++;

		foreach(PhraseLocation::$phraseLocations as $location) {
			if (
				$location->start == $start
				&& $location->end == $end
				&& $location->phraseWords == $phraseWords
				&& $location->parentWords == $parentWords
			) {
				$this->index++;
			}
		}
	}

} 