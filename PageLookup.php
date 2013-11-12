<?php
namespace FutureLink;

class PageLookup extends Feed_Abstract
{
	var $type = 'futurelink';
	var $futureLink = array();
	var $version = 0.1;

	static function futureLink($futureLink = array())
	{
		$me = new self($futureLink->href);
		$me->futureLink = $futureLink;
		return $me;
	}

	static function wikiView($args)
	{
		return;
		global $tikilib, $headerlib;

		 static $FutureLink_PageLookup = 0;
		++$FutureLink_PageLookup;

		$wikiAttributes = (new Tracker_Query('Wiki Attributes'))
			->byName()
			->excludeDetails()
			->filter(array('field'=> 'Type', 'value'=> 'FutureLink'))
			->filter(array('field'=> 'Page', 'value'=> $args['object']))
			->render(false)
			->query();

		$futureLinks = array();

		foreach ($wikiAttributes as $wikiAttribute) {
			$futureLinks[] = $futureLink = json_decode($wikiAttribute['Value']);

			if (isset($futureLink->href)) {
				$futureLink->href = urldecode($futureLink->href);

                //TODO: this shouldn't work, need to upgrade
				$result = FutureLink_SendToFuture::send(
					array(
						'futureLink'=> $futureLink,
						'pastlink'=> array(
							'body'=> $args['data'],
							'href'=> $tikilib->tikiUrl() . 'tiki-index.php?page=' . $args['object']
						)
					)
				);
			}
		}
	}
}
