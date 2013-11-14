<?php
global $tikilib, $headerlib, $prefs, $user;

$headerlib
    ->add_jsfile('vendor/rangy/rangy/uncompressed/rangy-core.js')
    ->add_jsfile('vendor/rangy/rangy/uncompressed/rangy-cssclassapplier.js')
    ->add_jsfile('vendor/rangy/rangy/uncompressed/rangy-selectionsaverestore.js')
    ->add_jsfile('lib/rangy_tiki/rangy-phraser.js')
    ->add_jsfile('lib/ZeroClipboard.js')
    ->add_jsfile('lib/core/JisonParser/Phraser.js')
    ->add_jsfile('vendor/jquery/md5/md5.js');

$page = $args['object'];
$version = $args['version'];

$futureLink = new FLP\FutureLink($page);

$phrase = (!empty($_POST['phrase']) ? $_POST['phrase'] : '');
FLP\Search::goToNewestWikiRevision($version, $phrase);
FLP\Search::restoreFutureLinkPhrasesInWikiPage($futureLink->getItems(), $phrase);

include 'futureLinkEditInterfaces.php';