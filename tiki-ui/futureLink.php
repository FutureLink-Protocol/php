<?php
function restoreFutureLinkPhrasesInWikiPage($items, Phraser\Phrase $text)
{
    //TODO: abstract
    global $tikilib, $headerlib, $smarty;
    $texts = array();
    $textMatchIndex = -1;

    $parsed = $smarty->getTemplateVars('parsed');
    if (empty($parsed)) {
        $parsed = $smarty->getTemplateVars('previewd');
    }

    foreach ($items as $i => $item) {
        if (!empty($item->pastlink->href)) {
            if (Phraser\Parser::hasPhrase($parsed, $item->futurelink->text) != true) {
                continue;
            }

            $phrases[] = $item->futurelink->text;

            $i = count($phrases) - 1;

            if ($text->sanitized == Phraser\Parser::superSanitize($item->futurelink->text)) {
                $phraseMatchIndex = $i;
            }

            $item->futurelink->dateLastUpdated = $tikilib->get_short_datetime($item->futurelink->dateLastUpdated);
            $item->futurelink->dateOriginated = $tikilib->get_short_datetime($item->futurelink->dateOriginated);
            $item->pastlink->dateLastUpdated = $tikilib->get_short_datetime($item->pastlink->dateLastUpdated);
            $item->pastlink->dateOriginated = $tikilib->get_short_datetime($item->pastlink->dateOriginated);

            $headerlib->add_jq_onready(
                "var phrase = $('span.futurelinkMiddle".$i."')
						.addClass('ui-state-highlight');

					var phraseLink = $('<a><sup>&</sup></a>')
						.data('metadataHere', " . json_encode($item->futurelink) . ")
						.data('metadataThere', " . json_encode($item->pastlink) . ")
						.addClass('futurelinkA')
						.insertBefore(phrase.first());"
            );
        }
    }

    $phraser = new Phraser\Parser();
    $phraser->setCssWordClasses(
        array(
            'start'=>'futurelinkStart',
            'middle'=>'futurelinkMiddle',
            'end'=>'futurelinkEnd'
        )
    );

    if ($textMatchIndex > -1) {
        $headerlib->add_jq_onready(
            "var selection = $('span.futurelinkStart". $textMatchIndex.",span.futurelinkEnd".$textMatchIndex."').realHighlight();

				$('body,html').animate({
					scrollTop: selection.first().offset().top - 10
				});"
        );
    }

    self::restorePhrasesInWikiPage($phraser, $texts);
}