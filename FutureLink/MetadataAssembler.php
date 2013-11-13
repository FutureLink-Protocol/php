<?php
namespace FutureLink;

use Phraser;

class MetadataAssembler
{
	static $acceptableKeys = array(
		'websiteTitle' =>               true,
		'websiteSubtitle' =>            true,
		'moderator' =>                  true,
		'moderatorInstitution' =>       true,
		'moderatorProfession' =>        true,
		'hash' =>                       true,
		'author' =>                     true,
		'authorInstitution' =>          true,
		'authorProfession' =>           true,
		"href" =>                       true,
		'answers' =>                    true,
		'dateLastUpdated' =>            true,
		'dateOriginated' =>             true,
		'language' =>                   true,
		'count' =>                      true,
		'keywords' =>                   true,
		'categories' =>                 true,
		'scientificField' =>            true,
		'minimumMathNeeded' =>          true,
		'minimumStatisticsNeeded' =>    true,
		'text' =>                       true
	);

	function __construct($name)
	{
		$this->name = $name;
	}

    static function fromRawToMetaData($raw)
    {
        $me = new Metadata();
        $me->websiteTitle =             $raw->websiteTitle;
        $me->websiteSubtitle =          $raw->websiteSubtitle;
        $me->moderator =                $raw->moderator;
        $me->moderatorInstitution=      $raw->moderatorInstitution;
        $me->moderatorProfession=       $raw->moderatorProfession;
        $me->hash=                      $raw->hash;
        $me->author=                    $raw->author;
        $me->dateLastUpdated=           $raw->dateLastUpdated;
        $me->authorProfession=          $raw->authorProfession;
        $me->href= 	                    $raw->href;
        $me->answers=                   $raw->answers;
        $me->dateLastUpdated=           $raw->dateLastUpdated;
        $me->dateOriginated=            $raw->dateOriginated;
        $me->language=                  $raw->language;
        $me->count=                     $raw->countAll;
        $me->keywords=                  $raw->keywords;
        $me->categories=                $raw->categories;
        $me->scientificField=           $raw->scientificField;
        $me->minimumMathNeeded=         $raw->minimumMathNeeded;
        $me->minimumStatisticsNeeded=   $raw->minimumStatisticsNeeded;
        $me->text= 	                    $raw->text;

        return $me;
    }

	static function pastLink($name, $data)
	{
		$me = new MetadataAssembler($name);
        $me->raw = new Metadata();
        Events::triggerMetaLookup($name, 'PastLink', $me->raw);
		$me->raw->text = $data;

		return $me;
	}

	static function futureLink($name)
	{
		$me = new MetadataAssembler($name);
        $me->raw = new Metadata();
        Events::triggerMetaLookup($name, 'FutureLink', $me->raw);

		return $me;
	}
}
