<?php
namespace FLP;

use Phraser;

class Events
{
	//possible events, I hate to re-declare all of them, but it is strongly typed, what can you say
	private static $Accepted = array();
	private static $CreateRevision = array();
	private static $MetadataLookup = array();
	private static $MetaSet = array();
	private static $FeedLookup = array();
	private static $FeedSave = array();
	private static $RevisionLookup = array();
	private static $Receive = array();
	private static $Send = array();
	private static $FilterPreviouslyVerified = array();

	public static function bind(&$event)
	{
		//reduce to fully qualified class name, then remove WikiLingoEvent from front
        $eventName = substr(str_replace("\\", "", get_class($event)), 8);
		Events::${$eventName}[] =& $event;
	}

    public static function triggerAccepted(Pair &$pair)
    {
        foreach(Events::$Accepted as &$event)
        {
            $event->trigger($pair);
        }
    }

    public static function triggerCreateRevision($page, $body, $version)
    {
        foreach(Events::$CreateRevision as &$event)
        {
            $event->trigger($page, $body, $version);
        }
    }

    public static function triggerMetadataLookup($linkType, &$value)
    {
        foreach(Events::$MetadataLookup as &$event)
        {
            $event->trigger($linkType, $value);
        }
    }

    public static function triggerMetadataSet($item, &$value)
    {
        foreach(Events::$MetaSet as &$event)
        {
            $event->trigger($item, $value);
        }
    }

	public static function triggerFeedLookup($name)
	{
		foreach(Events::$FeedLookup as &$event)
		{
			$event->trigger($name);
		}
	}

	public static function triggerFeedSave($name, $contents)
	{
		foreach(Events::$FeedSave as &$event)
		{
			$event->trigger($name, $contents);
		}
	}

    public static function triggerRevisionLookup(Phraser\Phrase $text, &$exists, Revision &$revision)
    {
        foreach(Events::$RevisionLookup as &$event)
        {
            $event->trigger($text, $exists, $revision);
        }
    }

    public static function triggerReceive($url, $params, &$result, &$data, &$items)
    {
        foreach(Events::$Receive as &$event)
        {
            $event->trigger($url, $params, $result, $data, $items);
        }
    }

    public static function triggerSend($url, $params, &$result, &$data, &$items)
    {
        foreach(Events::$Send as &$event)
        {
            $event->trigger($url, $params, $result, $data, $items);
        }
    }

	public static function triggerSuccess(Pair &$pair)
	{
		foreach(Events::$Send as &$event)
		{
			$event->trigger($pair);
		}
	}

	public static function triggerFilterPreviouslyVerified(Pair &$pair, &$exists)
	{
		foreach(Events::$FilterPreviouslyVerified as &$event)
		{
			$event->trigger($pair, $exists);
		}
	}
}