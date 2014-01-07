<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 11/13/13
 * Time: 1:01 PM
 */

namespace FLP\Event;

use FLP\Revision;
use Phraser\Phrase;

/**
 * Class RevisionLookup
 * @package FLP\Event
 */
class RevisionLookup extends Base
{
    /**
     * @param Phrase $text
     * @param Boolean $exists
     * @param Revision $revision
     */
    public function trigger(Phrase &$text, &$exists, Revision &$revision)
	{
		foreach($this->delegates as &$delegate)
		{
			$delegate($text, $exists, $revision);
		}
	}
} 