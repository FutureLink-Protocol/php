<?php
namespace FutureLink;

// Purpose: Locate, highlight, and scroll to requested FutureLink destination.  Add superscripted ForwardLink
//          indicators wherever FutureLinks exist within displayed page.
use Phraser;

class Search
{
	var $type = "futurelink";
	var $version = 0.1;
	var $page = '';

	function __construct($page)
	{
		$this->page = $page;
	}

	static function goToNewestWikiRevision($version, &$phrase)
	{
		if (!isset($_SESSION)) {
			session_start();
		}

		if (!empty($_SESSION['phrase'])) { //recover from redirect if it happened
			$phrase = $_SESSION['phrase'];
			unset($_SESSION['phrase']);
			return;
		}

		if (empty($phrase)) return;

        // if successful, will return an array with page, version, data, date, and phrase
        $newestRevision = Search::findWikiRevision($phrase);

		if ($newestRevision == false) {
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

		if ($version != $newestRevision['version']) {
			$_SESSION['phrase'] = $phrase; //prep for redirect if it happens;

			header('Location: ' . TikiLib::tikiUrl() . 'tiki-pagehistory.php?page=' . $newestRevision['page'] . '&preview=' . $newestRevision['version'] . '&nohistory');
			exit();
		}
	}

	static function findRevision($phrase)
	{
        $name = '';
        $version = '';
        $data = '';
        $date = '';

		$phrase = Phraser\Parser::superSanitize($phrase);

        // This query will *ALWAYS* fail if the destination page had been created/edited *PRIOR* to applying the 'Simple Wiki Attributes' profile!
        // Just recreate the destination page after having applied the profile in order to load it with the proper attributes.
        Events::triggerLookupRevision($phrase, $name, $version, $data, $date);

        // TODO: consider adding a test on query failure in order to determine whether:
        //       1) the phrase isn't found, or
        //       2) the Simple Wiki Attributes profile wasn't in place at page-creation
        // ...then display a more meaningful error message
		if (empty($name)) return false; //couldn't find it

		return array(
			'name' => $name,
			'version' => $version,
			'data' => $data,
			'date' => $date,
			'phrase' => $phrase
		);
	}


	static function restoreFutureLinkPhrasesInWikiPage($items, $phrase = "")
	{
        //TODO: abstract
		global $tikilib, $headerlib, $smarty;
		$phrase = Phraser\Parser::superSanitize($phrase);
		$phrases = array();
		$phraseMatchIndex = -1;

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

				if (Phraser\Parser::superSanitize($phrase) == Phraser\Parser::superSanitize($item->futurelink->text)) {
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

		if ($phraseMatchIndex > -1) {
			$headerlib->add_jq_onready(
				"var selection = $('span.futurelinkStart". $phraseMatchIndex.",span.futurelinkEnd".$phraseMatchIndex."').realHighlight();

				$('body,html').animate({
					scrollTop: selection.first().offset().top - 10
				});"
			);
		}

		self::restorePhrasesInWikiPage($phraser, $phrases);
	}

	static function restorePastLinkPhrasesInWikiPage($items, $phrase = "")
	{
		global $tikilib, $headerlib, $smarty;
		$phrase = Phraser\Parser::superSanitize($phrase);
		$phrases = array();
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

				if (Phraser\Parser::superSanitize($phrase) == Phraser\Parser::superSanitize($item->pastlink->text)) {
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

		self::restorePhrasesInWikiPage($phraser, $phrases);
	}

	static function restorePhrasesInWikiPage(Phraser\Parser $phraser, $phrases)
	{
		global $headerlib, $smarty;
		//TODO - not sure the tablesorter js and css files need to be loaded since they are loaded in tiki-setup
		$headerlib
			->add_jsfile('vendor/jquery/plugins/tablesorter/js/jquery.tablesorter.js')
			->add_cssfile('lib/jquery_tiki/tablesorter/style.css')
			->add_jq_onready(
<<<JQ
				$('a.futurelinkA,a.pastlinkA')
					.css('cursor', 'pointer')
					.click(function() {
						var me = $(this),
						metadataHere = me.data('metadataHere'),
						metadataThere = me.data('metadataThere');

						var table = $('<table class="tablesorter" style="width: 100%;"/>'),
						    thead = $('<thead><tr /></thead>').appendTo(table),
						    tbody = $('<tbody><tr /></tbody>').appendTo(table),
						    form;

						function a(head, body) {
							$('<th />').text(head).appendTo(thead.find('tr'));

							$('<td />').html(body).appendTo(tbody.find('tr'));
						}

						a(tr('Sentence text'), metadataThere.text);
						a(tr('Date Created'), metadataThere.dateOriginated);
						a(tr('Date Updated Here'), metadataHere.dateLastUpdated);
						a(tr('Date Updated There'), metadataThere.dateLastUpdated);
						a(tr('Click below to read Citing blocks'), '<input type="submit" class="btn btn-default" value="' + tr('Read') + '" />');

						form = $('<form method="POST" />')
							.attr('action', metadataThere.href)
							.append($('<input type="hidden" name="phrase" />').val(metadataThere.text))
							.append(table)
							.dialog({
								title: tr('Linked to: ') + metadataHere.text,
								modal: true,
								width: $(window).width() * 0.8
							});

						return false;
					});
JQ
				,
				100
			);

		$parsed = $smarty->getTemplateVars('parsed');
		if (!empty($parsed)) {
			$smarty->assign('parsed', $phraser->findPhrases($parsed, $phrases));
		} else {
			$previewd = $smarty->getTemplateVars('previewd');
			if (!empty($previewd)) {
				$previewd = $phraser->findPhrases($previewd, $phrases);
				$smarty->assign('previewd', $previewd);
			}
		}
	}
}
