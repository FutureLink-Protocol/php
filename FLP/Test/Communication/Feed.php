<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 11/21/13
 * Time: 10:47 AM
 */

namespace FLP\Test\Communication;

Use RedBean_Facade as R;
use FLP\Data;
use FLP\Test\Base;

class Feed extends Base
{
	public function __construct()
	{
		$this->expected = 1;
		Data::wipe();
		chdir(dirname(__FILE__));
		chdir('../../../default-ui');
		require "past.setup.php";
		require "future.setup.php";
		require "past.setup.php";

		$pairsRaw = R::findAll('flpPair',' title = ? ', array('The FutureLink-Protocol'));
		$this->actual = count($pairsRaw);
	}
} 