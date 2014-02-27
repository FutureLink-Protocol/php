<?php
namespace FLP;

use Phraser;

/**
 * Class MetadataAssembler
 * @package FLP
 */
class MetadataAssembler
{
    public $page;
    public $lang;
    public $lastModif;
    public $href;
    public $websiteTitle;
    public $moderatorData;
    public $authorData;
    public $raw;
    public $minimumStatisticsNeeded;
    public $minimumMathNeeded;
    public $scientificField;
    public $categories = array();
    public $keywords;
    public $questions;
    public $datePageOriginated;
    public $countAll;
    public $language = '';
    public $findDatePageOriginated;

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
        $me->count=                     $raw->count;
        $me->keywords=                  $raw->keywords;
        $me->categories=                $raw->categories;
        $me->scientificField=           $raw->scientificField;
        $me->minimumMathNeeded=         $raw->minimumMathNeeded;
        $me->minimumStatisticsNeeded=   $raw->minimumStatisticsNeeded;
        $me->text= 	                    $raw->text;

        return $me;
    }


    /**
     * @param $data
     * @return MetadataAssembler
     */
    static function past($data)
	{
		$me = new MetadataAssembler();
        $me->raw = new Metadata();
        Events::triggerMetadataLookup('Past', $me->raw);
		$me->raw->text = $data;

		return $me;
	}

    /**
     * @return MetadataAssembler
     */
    static function future()
	{
		$me = new MetadataAssembler();
        $me->raw = new Metadata();
        Events::triggerMetadataLookup('Future', $me->raw);

		return $me;
	}
}
