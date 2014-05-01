<?php
namespace FLP;

use Phraser;

/**
 * Class Events
 * @package FLP
 */
class Events
{
	//possible events, I hate to re-declare all of them, but it is strongly typed, what can you say
	private static $Accepted = array();
	private static $CreateRevision = array();
	private static $MetadataLookup = array();
	private static $MetaSet = array();
	private static $FeedLookup = array();
	private static $FeedSave = array();
	private static $Receive = array();
	private static $FilterPreviouslyVerified = array();

    /**
     * @param String $event
     */
    public static function bind(&$event)
	{
		//reduce to fully qualified class name, then remove WikiLingoEvent from front
        $eventName = substr(str_replace("\\", "", get_class($event)), 8);
		Events::${$eventName}[] =& $event;
	}

    /**
     * @param Pair $pair
     */
    public static function triggerAccepted(Pair &$pair)
    {
        foreach(Events::$Accepted as &$event)
        {
            $event->trigger($pair);
        }
    }

    /**
     * @param String $page
     * @param String $body
     * @param String $version
     */
    public static function triggerCreateRevision($page, $body, $version)
    {
        foreach(Events::$CreateRevision as &$event)
        {
            $event->trigger($page, $body, $version);
        }
    }

    /**
     * @param $linkType
     * @param $value
     */
    public static function triggerMetadataLookup($linkType, &$value)
    {
        foreach(Events::$MetadataLookup as &$event)
        {
            $event->trigger($linkType, $value);
        }
    }

    /**
     * @param $item
     * @param $value
     */
    public static function triggerMetadataSet($item, &$value)
    {
        foreach(Events::$MetaSet as &$event)
        {
            $event->trigger($item, $value);
        }
    }

    /**
     * @param $name
     */
    public static function triggerFeedLookup($name)
	{
		foreach(Events::$FeedLookup as &$event)
		{
			$event->trigger($name);
		}
	}

    /**
     * @param $name
     * @param $contents
     * @param $contents
     */
    public static function triggerFeedSave($name, $contents)
	{
		foreach(Events::$FeedSave as &$event)
		{
			$event->trigger($name, $contents);
		}
	}

    /**
     * @param $url
     * @param $params
     * @param $result
     * @param $data
     * @param $items
     */
    public static function triggerReceive($url, $params, &$result, &$data, &$items)
    {
        foreach(Events::$Receive as &$event)
        {
            $event->trigger($url, $params, $result, $data, $items);
        }
    }


    /**
     * @param Pair $pair
     */
    public static function triggerSuccess(Pair &$pair)
	{
		foreach(Events::$Send as &$event)
		{
			$event->trigger($pair);
		}
	}

    /**
     * @param Pair $pair
     * @param $exists
     */
    public static function triggerFilterPreviouslyVerified(Pair &$pair, &$exists)
	{
		foreach(Events::$FilterPreviouslyVerified as &$event)
		{
			$event->trigger($pair, $exists);
		}
	}
}