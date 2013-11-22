<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 11/13/13
 * Time: 1:01 PM
 */

namespace FLP\Event;

use FLP\Pair;

class FilterPreviouslyVerified extends Base
{
	public function trigger(Pair &$pair, &$exists)
	{
		foreach($this->delegates as &$delegate)
		{
			$delegate($pair, $exists);
		}
	}
} 