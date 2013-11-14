<?php
global $tikilib, $headerlib, $prefs, $user;

$answers = json_encode($this->metadata->raw->answers);

$clipboarddata = json_encode($this->metadata->raw);

$page = urlencode($this->page);

$headerlib->add_jq_onready(
<<<JQ
    var answers = $answers,

    createPastLinkButton = $('.pastlinkCreationButton');

    if (!createPastLinkButton.length) {
        createPastLinkButton = $('<div />')
            .appendTo('body')
            .text(tr('Create PastLink'))
            .addClass('pastlinkCreationButton')
            .css('position', 'fixed')
            .css('top', '0px')
            .css('font-size', '10px')
            .css('z-index', 99999)
            .fadeTo(0, 0.85)
            .button();
    }

    createPastLinkButton
        .click(function() {
            $(this).remove();
            $.notify(tr('Highlight text to be linked'));

            $(document).bind('mousedown', function() {
                if (me.data('rangyBusy')) return;
                $('div.pastlinkCreate').remove();
                $('embed[id*="ZeroClipboard"]').parent().remove();
            });

            var me = $('#page-data').rangy(function(o) {
                if (me.data('rangyBusy')) return;
                o.text = $.trim(o.text);

                var pastlinkCreate = $('<div>' + tr('Accept PastLink') + '</div>')
                    .button()
                    .addClass('pastlinkCreate')
                    .css('position', 'absolute')
                    .css('top', o.y + 'px')
                    .css('left', o.x + 'px')
                    .css('font-size', '10px')
                    .fadeTo(0,0.80)
                    .mousedown(function() {
                        var suggestion = $.trim(rangy.expandPhrase(o.text, '\\n', me[0]));
                        var buttons = {};

                        if (suggestion == o.text) {
                            getAnswers();
                        } else {
                            buttons[tr('Ok')] = function() {
                                o.text = suggestion;
                                me.box.dialog('close');
                                getAnswers();
                            };

                            buttons[tr('Cancel')] = function() {
                                me.box.dialog('close');
                                getAnswers();
                            };

                            me.box = $('<div>' +
                                '<table>' +
                                    '<tr>' +
                                        '<td>' + tr('You selected:') + '</td>' +
                                        '<td><b>"</b>' + o.text + '<b>"</b></td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td>' + tr('Suggested selection:') + '</td>' +
                                        '<td class="ui-state-highlight"><b>"</b>' + suggestion + '<b>"</b></td>' +
                                    '</tr>' +
                                '</tabl>' +
                            '</div>')
                                .dialog({
                                    title: tr("Suggestion"),
                                    buttons: buttons,
                                    width: $(window).width() / 2,
                                    modal: true
                                })
                        }

                        function getAnswers() {
                            if (!answers.length) {
                                return acceptPhrase();
                            }

                            var answersDialog = $('<table width="100%;" />');

                            $.each(answers, function() {
                                var tr = $('<tr />').appendTo(answersDialog);
                                $('<td style="font-weight: bold; text-align: left;" />')
                                    .text(this.question)
                                    .appendTo(tr);

                                $('<td style="text-align: right;"><input class="answerValues" style="width: inherit;"/></td>')
                                    .appendTo(tr);
                            });

                            var answersDialogButtons = {};
                            answersDialogButtons[tr("Ok")] = function() {
                                $.each(answers, function(i) {
                                    answers[i].answer = escape(answersDialog.find('.answerValues').eq(i).val());
                                });

                                answersDialog.dialog('close');

                                acceptPhrase();
                            };

                            answersDialog.dialog({
                                title: tr("Please fill in the questions below"),
                                buttons: answersDialogButtons,
                                modal: true,
                                width: $(window).width() / 2
                            });
                        }

                        //var timestamp = '';

                        function acceptPhrase() {
                            /* Will integrate when timestamping works
                            $.modal(tr("Please wait while we process your request..."));
                            $.getJSON("tiki-index.php", {
                                action: "timestamp",
                                hash: hash,
                                page: '$page'
                            }, function(json) {
                                timestamp = json;
                                $.modal();
                                makeClipboardData();
                            });
                            */
                            makeClipboardData();
                        }

                        function encode(s){
                            for(var c, i = -1, l = (s = s.split("")).length, o = String.fromCharCode; ++i < l;
                                s[i] = (c = s[i].charCodeAt(0)) >= 127 ? o(0xc0 | (c >>> 6)) + o(0x80 | (c & 0x3f)) : s[i]
                            );
                            return s.join("");
                        }

                        function makeClipboardData() {

                            var clipboarddata = $clipboarddata;

                            clipboarddata.text = encode((o.text + '').replace(/\\n/g, ''));

                            clipboarddata.hash = md5(
                                rangy.superSanitize(
                                    clipboarddata.author +
                                    clipboarddata.authorInstitution +
                                    clipboarddata.authorProfession
                                )
                            ,
                                rangy.superSanitize(clipboarddata.text)
                            );

                            me.data('rangyBusy', true);

                            var pastlinkCopy = $('<div></div>');
                            var pastlinkCopyButton = $('<div>' + tr('Click HERE to Copy to Clipboard') + '</div>')
                                .button()
                                .appendTo(pastlinkCopy);
                            var pastlinkCopyValue = $('<textarea style="width: 100%; height: 80%;"></textarea>')
                                .val(encodeURI(JSON.stringify(clipboarddata)))
                                .appendTo(pastlinkCopy);

                            pastlinkCopy.dialog({
                                title: tr("Copy text and Metadata"),
                                modal: true,
                                close: function() {
                                    me.data('rangyBusy', false);
                                    $(document).mousedown();
                                },
                                draggable: false
                            });

                            pastlinkCopyValue.select().focus();

                            var clip = new ZeroClipboard.Client();
                            clip.setHandCursor( true );

                            clip.addEventListener('complete', function(client, text) {
                                pastlinkCreate.remove();
                                pastlinkCopy.dialog( "close" );
                                clip.hide();
                                me.data('rangyBusy', false);


                                $.notify(tr('Text and Metadata copied to Clipboard'));
                                return false;
                            });

                            clip.glue( pastlinkCopyButton[0] );

                            clip.setText(pastlinkCopyValue.val());


                            $('embed[id*="ZeroClipboard"]').parent().css('z-index', '9999999999');
                        }
                    })
                    .appendTo('body');
            });
    });
JQ
);