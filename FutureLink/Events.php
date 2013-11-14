<?php
namespace FutureLink;

use Phraser;

class Events
{
	//possible events, I hate to re-declare all of them, but it is strongly typed, what can you say
	private static $Accepted = array();
	private static $CreateRevision = array();
	private static $MetaLookup = array();
	private static $MetaSet = array();
	private static $LookupRevision = array();
	private static $Receive = array();
	private static $Send = array();

	public static function bind(&$event)
	{
		//reduce to fully qualified class name, then remove WikiLingoEvent from front
        $eventName = substr(str_replace("\\", "", get_class($event)), 15);
		self::${$eventName}[] =& $event;
	}

    public static function triggerAccepted($name, Phraser\Phrase $text)
    {
        foreach(self::$Accepted as &$event)
        {
            $event->trigger($name, $text);
        }
    }

    public static function triggerCreateRevision($page, $body, $version)
    {
        foreach(self::$CreateRevision as &$event)
        {
            $event->trigger($page, $body, $version);
        }
    }

    public static function triggerMetaLookup($objectName, $linkType, &$value)
    {
        foreach(self::$MetaLookup as &$event)
        {
            $event->trigger($objectName, $linkType, $value);
        }
    }

    public static function triggerMetaSet($objectName, $item, &$value)
    {
        foreach(self::$MetaSet as &$event)
        {
            $event->trigger($objectName, $item, $value);
        }
    }

    public static function triggerLookupRevision(Phraser\Phrase $text, Revision &$revision)
    {
        foreach(self::$LookupRevision as &$event)
        {
            $event->trigger($text, $revision);
        }
    }

    public static function triggerReceive($url, $params, &$result, &$data, &$items)
    {
        foreach(self::$Receive as &$event)
        {
            $event->trigger($url, $params, $result, $data, $items);
        }
    }

    public static function triggerSend($url, $params, &$result, &$data, &$items)
    {
        foreach(self::$Send as &$event)
        {
            $event->trigger($url, $params, $result, $data, $items);
        }
    }
}