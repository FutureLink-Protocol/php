<?php
function restorePastLinkPhrasesInWikiPage($items, Phraser\Phrase $text)
{
    global $tikilib, $headerlib, $smarty;
    $texts = array();
    $phraseMatchIndex = -1;

    $parsed = $smarty->getTemplateVars('parsed');
    if (empty($parsed)) {
        $parsed = $smarty->getTemplateVars('previewd');
    }

    foreach ($items as &$item) {
        if (!empty($item->futurelink->href)) {
            if (Phraser\Parser::hasPhrase($parsed, $item->pastlink->text) != true) {
                continue;
            }

            $phrases[] = $item->pastlink->text;

            $i = count($phrases) - 1;

            if ($text->sanitized == Phraser\Parser::superSanitize($item->pastlink->text)) {
                $phraseMatchIndex = $i;
            }

            $item->futurelink->dateLastUpdated = $tikilib->get_short_datetime($item->futurelink->dateLastUpdated);
            $item->futurelink->dateOriginated = $tikilib->get_short_datetime($item->futurelink->dateOriginated);
            $item->pastlink->dateLastUpdated = $tikilib->get_short_datetime($item->pastlink->dateLastUpdated);
            $item->pastlink->dateOriginated = $tikilib->get_short_datetime($item->pastlink->dateOriginated);

            $headerlib->add_jq_onready(
                "var phrase = $('span.pastlinkMiddle".$i."')
						.addClass('ui-state-highlight');

					var phraseLink = $('<a><sup>&</sup></a>')
						.data('metadataHere', " . json_encode($item->pastlink) . ")
						.data('metadataThere', " . json_encode($item->futurelink) . ")
						.addClass('pastlinkA')
						.insertAfter(phrase.last());"
            );
        }
    }

    $phraser = new Phraser\Parser();

    $phraser->setCssWordClasses(
        array(
            'start'=>'pastlinkStart',
            'middle'=>'pastlinkMiddle',
            'end'=>'pastlinkEnd'
        )
    );

    if ($phraseMatchIndex > -1) {
        $headerlib->add_jq_onready(
            "var selection = $('span.pastlinkStart".$phraseMatchIndex.",span.pastlinkEnd".$phraseMatchIndex."').realHighlight();

				$('body,html').animate({
					scrollTop: selection.first().offset().top - 10
				});"
        );
    }

    self::restorePhrasesInWikiPage($phraser, $texts);
}