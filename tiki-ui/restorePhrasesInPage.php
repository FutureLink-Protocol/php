<?php
function restorePhrasesInWikiPage(Phraser\Parser $phraser, $texts)
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
        $smarty->assign('parsed', $phraser->findPhrases($parsed, $texts));
    } else {
        $previewd = $smarty->getTemplateVars('previewd');
        if (!empty($previewd)) {
            $previewd = $phraser->findPhrases($previewd, $texts);
            $smarty->assign('previewd', $previewd);
        }
    }
}