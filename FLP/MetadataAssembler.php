<?php
namespace FLP;

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

    static function fromJSONToMetaData($json)
    {
        $me = new Metadata();
        $me->websiteTitle =             $json->websiteTitle;
        $me->websiteSubtitle =          $json->websiteSubtitle;
        $me->moderator =                $json->moderator;
        $me->moderatorInstitution=      $json->moderatorInstitution;
        $me->moderatorProfession=       $json->moderatorProfession;
        $me->hash=                      $json->hash;
        $me->author=                    $json->author;
        $me->dateLastUpdated=           $json->dateLastUpdated;
        $me->authorProfession=          $json->authorProfession;
        $me->href= 	                    $json->href;
        $me->answers=                   $json->answers;
        $me->dateLastUpdated=           $json->dateLastUpdated;
        $me->dateOriginated=            $json->dateOriginated;
        $me->language=                  $json->language;
        $me->count=                     $json->count;
        $me->keywords=                  $json->keywords;
        $me->categories=                $json->categories;
        $me->scientificField=           $json->scientificField;
        $me->minimumMathNeeded=         $json->minimumMathNeeded;
        $me->minimumStatisticsNeeded=   $json->minimumStatisticsNeeded;
        $me->text= 	                    $json->text;

        return $me;
    }

	static function past($data)
	{
		$me = new MetadataAssembler();
        $me->raw = new Metadata();
        Events::triggerMetadataLookup('Past', $me->raw);
		$me->raw->text = $data;

		return $me;
	}

	static function future()
	{
		$me = new MetadataAssembler();
        $me->raw = new Metadata();
        Events::triggerMetadataLookup('Future', $me->raw);

		return $me;
	}
}
