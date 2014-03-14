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
		$article->sanitized = Phraser\Phraser::superSanitize($body);
		$article->metadata = json_encode($metadata);
		$article->version = $version;
		R::store($article);
	}

	public static function getRevision($text)
	{
		$phrase = Phraser\Phraser::superSanitize($text);

		if (!self::$initiated) self::setup();

		$found = R::getRow(<<<SQL
SELECT * FROM flpArticle WHERE sanitized LIKE ? ORDER BY version DESC LIMIT 1
SQL
			,
			array( '%' . $phrase . '%')
		);

		if ($found) {
            //found is an array, not a bean
			$revision = new Revision(
                $found['title'],
                $found['version'],
                $found['body'],
                $found['sanitized']
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

		$phrase = Phraser\Phraser::superSanitize($pair->past->text);

		return R::findOne('flpPair',' sanitized = ? ', array($phrase));
	}

	public static function createPair($title, Pair $pair)
	{
		if (!self::$initiated) self::setup();

		//prep to go into database
		$pairAsJson = json_encode($pair);
		$phrase = Phraser\Phraser::superSanitize($pair->past->text);

		$flpPair = R::dispense('flpPair');
		$flpPair->title = $title;
		$flpPair->sanitized = $phrase;
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