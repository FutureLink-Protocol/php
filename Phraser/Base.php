<?php
namespace Phraser;

abstract class Base
{
	public $chars = array();
	public $words = array();
	public $currentWord = -1;
	public $wordsChars = array();
	public $locations = array();
	public $parsed = '';
	public $cache = array();

	public $cssClassStart = '';
	public $cssClassMiddle = '';
	public $cssClassEnd = '';

	public abstract function parse($input);

	function setCssWordClasses($classes = array())
	{
		$classes = array_merge(
			array(
				'start' => '',
				'middle' => '',
				'end' => ''
			),
			$classes
		);

		$this->cssClassStart = $classes['start'];
		$this->cssClassMiddle = $classes['middle'];
		$this->cssClassEnd = $classes['end'];

		return $this;
	}

	function tagHandler(ParserValue $value)
	{
		return $value->text;
	}

	function wordHandler(ParserValue $value)
	{
		$word = $value->text;
		$this->currentWord++;
		$this->words[] = $word;

		for ($i = 0, $end = count($this->locations); $i < $end; $i++) {
			if (empty($this->locations[$i]->ended)) {
				if ($this->currentWord >= $this->locations[$i]->start
						&& $this->currentWord <= $this->locations[$i]->end
				) {
					$word = '<span class="phrase phrase' . $i . (!empty($this->cssClassMiddle) ? ' '  . $this->cssClassMiddle . ' ' . $this->cssClassMiddle . $i : '') . '" style="border: none;">' . $word . '</span>';
				}

				if ($this->currentWord == $this->locations[$i]->start) {
					$word = '<span class="phraseStart phraseStart' . $i . (!empty($this->cssClassStart) ? ' '  . $this->cssClassStart . ' ' . $this->cssClassStart . $i : '') . '" style="border: none; font-weight: bold;"></span>' . $word;
				}

				if ($this->currentWord == $this->locations[$i]->end) {
					if (empty($this->wordsChars[$this->currentWord])) {
						$this->locations[$i]->ended = true;
						$word .= '<span class="phraseEnd phraseEnd' . $i . (!empty($this->cssClassEnd) ? ' '  . $this->cssClassEnd . ' ' . $this->cssClassEnd . $i : '') . '" style="border: none;"></span>';
					} else {
						$word = '<span class="phrase phrase' . $i . (!empty($this->cssClassMiddle) ? ' '  . $this->cssClassMiddle . ' ' . $this->cssClassMiddle . $i : '') . '" style="border: none;">' . $word . '</span>';
					}
				}
			}
		}

		return $word;
	}

	function charHandler(ParserValue $value)
	{
		$char = $value->text;
		if (empty($this->wordsChars[$this->currentWord])) $this->wordsChars[$this->currentWord] = "";

		//this line attempts to solve some character translation problems
		$char = iconv('UTF-8', 'ISO-8859-1', utf8_encode($char));

		$this->wordsChars[$this->currentWord] .= $char;
		$this->chars[] = $char;

		for ($i = 0, $end = count($this->locations); $i < $end; $i++) {
			if (empty($this->locations[$i]->ended)) {
				if ($this->currentWord >= $this->locations[$i]->start) {
					$char = '<span class="phrases phrase' . $i . (!empty($this->cssClassMiddle) ? ' '  . $this->cssClassMiddle . ' ' . $this->cssClassMiddle . $i : '') . '" style="border: none;">' . $char . '</span>';

					if ($this->currentWord == $this->locations[$i]->end) {
						if (!empty($this->wordsChars[$this->currentWord])) {
							$this->locations[$i]->ended = true;
							$char = $char . '<span class="phraseEnd phraseEnd' . $i . (!empty($this->cssClassEnd) ? ' '  . $this->cssClassEnd . ' ' . $this->cssClassEnd . $i : '') . '" style="border: none;"></span>';
						}
					}
				}
			}
		}


		return $char;
	}

	function isUnique($parent, $phrase)
	{
		$parentWords = $this->sanitizeToWords($parent);
		$phraseWords = $this->sanitizeToWords($phrase);

		$this->clearIndexes();

		$this->addIndexes($parentWords, $phraseWords);

		if (count($this->locations) > 1) {
			return false;
		} else {
			return true;
		}
	}

	function findPhrases($parent, $phrases)
	{
		$parentWords = $this->sanitizeToWords($parent);
		$phrasesWords = array();

		$this->clearIndexes();

		foreach ($phrases as $phrase) {
			$phraseWords = $this->sanitizeToWords($phrase->value);
			$this->addIndexes($parentWords, $phraseWords);
			$phrasesWords[] = $phraseWords;
		}

		if (!empty($this->locations)) {
			$parent = $this->parse($parent);
		}

		return $parent;
	}

	function clearIndexes()
	{
		$this->locations = array();
	}

	function addIndexes($parentWords, $phraseWords)
	{
		$phraseLength = count($phraseWords) - 1;
		$phraseConcat = implode($phraseWords, '|');
		$parentConcat = implode($parentWords, '|');

		$boundaries = explode($phraseConcat, $parentConcat);

		//We may not have a match
		if (count($boundaries) == 1 && strlen($boundaries[0]) == strlen($parentConcat)) {
			return array();
		}

		for ($i = 0, $j = count($boundaries); $i < $j; $i++) {
			$boundaryLength = substr_count($boundaries[$i], '|');

			$this->locations[] = new PhraseLocation(
				min(count($parentWords) - count($phraseWords), $boundaryLength),
				min(count($parentWords), $boundaryLength + $phraseLength),
				$phraseWords,
				$parentWords
			);

			$i++;
		}
	}

	static function hasPhrase($parent, $phrase)
	{
		$parent = self::sanitizeToWords($parent);
		$phrase = self::sanitizeToWords($phrase);

		$parent = implode('|', $parent);
		$phrase = implode('|', $phrase);

		return (strpos($parent, $phrase) !== false);
	}

	static $sanitizedWords;

	static function sanitizeToWords($html)
	{
		$html = utf8_encode($html);
		if (isset(self::$sanitizedWords[$html])) return self::$sanitizedWords[$html];

		$sanitized = preg_replace('/<(.|\n)*?>/', ' ', $html);
		$sanitized = preg_replace('/\W/', ' ', $sanitized);
		$sanitized = explode(" ", $sanitized);
		$sanitized = array_values(array_filter($sanitized, 'strlen'));

		self::$sanitizedWords[$html] = $sanitized;

		return $sanitized;
	}

	static function superSanitize($html)
	{
		return implode('', self::sanitizeToWords($html));
	}
}
