<?php
function goToNewestWikiRevision($version, Phraser\Phrase &$text = null)
{
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!empty($_SESSION['phrase'])) { //recover from redirect if it happened
        $text = new Phraser\Phrase($_SESSION['phrase']);
        unset($_SESSION['phrase']);
        return;
    }

    if (empty($text)) return;

    // if successful, will return an array with page, version, data, date, and phrase
    $revision = new FLP\Revision();
    FLP\Events::triggerLookupRevision($text, $revision);

    if ($revision->version == null) {
        //TODO: abstract
        TikiLib::lib("header")->add_jq_onready(
            <<<JQ
            				$('<div />')
					.html(
						tr('This can happen if the page you are linking to has changed since you obtained the futurelink or if the rights to see it are different from what you have set at the moment.') +
						'&nbsp;&nbsp;' +
						tr('If you are logged in, try logging out and then recreate the futurelink.')
					)
					.dialog({
						title: tr('Phrase not found'),
						modal: true
					});
JQ
        );
        return;
    }

    if ($version != $revision->version) {
        $_SESSION['phrase'] = $text->original; //prep for redirect if it happens;

        header('Location: ' . TikiLib::tikiUrl() . 'tiki-pagehistory.php?page=' . $revision->name . '&preview=' . $revision->version . '&nohistory');
        exit();
    }
}