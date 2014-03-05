<?php
namespace FLP;

use RedBean_Facade as R;
use Phraser;

class Data
{
	private static $initiated = false;

	public static $dbConnectionString = NULL;
	public static $dbUsername = NULL;
	public static $dbPassword = NULL;

	public static function setup()
	{
		R::setStrictTyping(false);
		R::setup(self::$dbConnectionString, self::$dbUsername, self::$dbPassword);
		self::$initiated = true;
	}

	public static function wipe()
	{
		if (!self::$initiated) self::setup();

		R::wipe( 'flpArticle' );
		R::wipe( 'flpPair' );
	}

	public static function getArticleByTitle($title)
	{
		if (!self::$initiated) self::setup();

		$article = R::find('flpArticle',' title = ? ', array($title));
		return $article;
	}

	public static function createArticle($title, $body, $metadata, $version = 0)
	{
		if (!self::$initiated) self::setup();

		$article = R::dispense('flpArticle');
		$article->title = $title;
		$article->body = $body;
		$article->sanitized = Phraser\Parser::superSanitize($body);
		$article->metadata = json_encode($metadata);
		$article->version = $version;
		R::store($article);
	}

	public static function getRevision($text)
	{
		$phrase = new Phraser\Phrase($text);

		if (!self::$initiated) self::setup();

		$found = R::findOne(
			'flpArticle',
			<<<SQL
sanitized LIKE ? ORDER BY version DESC
SQL
			,
			array( '%' . $phrase->sanitized . '%')
		);

		if ($found) {
			$revision = new Revision(
				$found->title,
				$found->version,
				$found->data,
				$found->date,
				$found->phrase
			);
			return $revision;
		}
		return null;
	}


	//pair
	public static function GetPairsByTitleAndApplyToUI($title, UI $ui)
	{
		if (!self::$initiated) self::setup();

		$pairsRaw = R::findAll('flpPair',' title = ? ', array($title));

		$pairs = array();

		foreach($pairsRaw as $pair) {
			$existingPair = new PairAssembler($pair->pair);
			$ui->addPhrase($existingPair->pastText);
			$pairs[] = $existingPair;
		}

		return $pairs;
	}

	public static function getPair(Pair $pair)
	{
		if (!self::$initiated) self::setup();

		$phrase = new Phraser\Phrase($pair->past->text);

		return R::findOne('flpPair',' sanitized = ? ', array($phrase->sanitized));
	}

	public static function createPair($title, Pair $pair)
	{
		if (!self::$initiated) self::setup();

		//prep to go into database
		$pairAsJson = json_encode($pair);
		$phrase = new Phraser\Phrase($pair->past->text);

		$flpPair = R::dispense('flpPair');
		$flpPair->title = $title;
		$flpPair->sanitized = $phrase->sanitized;
		$flpPair->pair = $pairAsJson;
		R::store($flpPair);
	}

	public static function createKeywords($title)
	{
		//TODO
	}

	public static function getKeywords($title)
	{
		//TODO
		return array();
	}

	public static function createScientificField($title)
	{
		//TODO
	}

	public static function getScientificField($title)
	{
		//TODO
		return array();
	}

	public static function createMinimumStatisticsNeeded($title)
	{
		//TODO
	}

	public static function getMinimumStatisticsNeeded($title)
	{
		//TODO
		return array();
	}
}