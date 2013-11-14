<?php
global $headerlib, $_REQUEST;

use FutureLink\Search;
use FutureLink\FutureUI;

$page = $args['object'];
$version = $args['version'];

$me = new FutureUI($page);

$phrase = (!empty($_POST['phrase']) ? $_POST['phrase'] : '');
Search::goToNewestWikiRevision($version, $phrase);
Search::restoreFutureLinkPhrasesInWikiPage($me->getItems(), $phrase);

include 'futureLinkEditInterfaces.php';