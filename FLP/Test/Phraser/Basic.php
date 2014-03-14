<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 3/14/14
 * Time: 3:28 PM
 */

namespace FLP\Test\Phraser;

use FLP\Test\Base;
use Phraser;

class Basic extends Base
{
    public function __construct()
    {
        $this->expected = "<p>This is my article
</p>

<p>@FLP(%7B%22websiteTitle%22:%22HomePage%22,%22websiteSubtitle%22:%22%22,%22moderator%22:%22System%20Administrator%22,%22moderatorInstitution%22:%22%22,%22moderatorProfession%22:%22%22,%22hash%22:%224707e4e64b25196398e99b852cc810cf%22,%22author%22:%22System%20Administrator%22,%22authorInstitution%22:%22%22,%22authorProfession%22:%22%22,%22href%22:%22http://localhost/p/tikiExp/tiki-index.php?page=HomePage%22,%22answers%22:%5B%5D,%22dateLastUpdated%22:%22%22,%22dateOriginated%22:%221394755770%22,%22language%22:null,%22count%22:0,%22keywords%22:%5B%5D,%22categories%22:null,%22scientificField%22:%22%22,%22minimumMathNeeded%22:%22%22,%22minimumStatisticsNeeded%22:%22%22,%22text%22:%22%20can%20change%20this%20page%20after%20logging%20in%22%7D)I can't wait to show it off!@)
</p>

<p>
<br />The end!
</p>";

        $phraser = new Phraser\Phraser();

        $this->actual = $phraser->parse($this->expected)->text;
    }
} 