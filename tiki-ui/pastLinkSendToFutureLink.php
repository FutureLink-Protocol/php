<?php
global $page, $headerlib;

$pastLink = new FLP\PastLink($page);
$phrase = (!empty($_REQUEST['phrase']) ? $_REQUEST['phrase'] : '');
FLP\Search::restorePastLinkPhrasesInWikiPage($pastLink->getItems(), new Phraser\Phrase($phrase));

//if we have an awaiting PastLink that needs sent, we do so here
$result = (new Tracker_Query('Wiki Attributes'))
    ->byName()
    ->render(false)
    ->filterFieldByValue('Page', $page)
    ->filterFieldByValue('Type', 'PastLink Send')
    ->query();

if (count($result) > 0) {
    foreach (FLP\SendToFuture::send($_SERVER['HTTP_HOST']) as $text => $received) {
        $receivedJSON = json_decode($received);
        if (isset($receivedJSON->feed) && $receivedJSON->feed == 'success') {
            (new Tracker_Query('Wiki Attributes'))
                ->byName()
                ->render(false)
                ->filterFieldByValue('Page', $page)
                ->filterFieldByValue('Type', 'PastLink Send')
                ->filterFieldByValue('Attribute', $text)
                ->delete(true);

            $headerlib->add_jq_onready("$.notify('" . tr("PastLink and FutureLink created...") . "');");
        }
    }
}

global $page;
//We add these to a stack that needs to be sent, rather than just sending all with the view event
$me = new self();

foreach ($me->getItems() as $item) {
    (new Tracker_Query('Wiki Attributes'))
        ->byName()
        ->replaceItem(
            array(
                'Page' => $page,
                'Attribute' => $item->pastlink->text,
                'Value' => 'true',
                'Type' => 'PastLink Send'
            )
        );
}