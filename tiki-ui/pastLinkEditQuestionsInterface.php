<?php
$questions = json_encode($questions);

TikiLib::lib('header')->add_jq_onready(
    <<<JQ
        var addQuestionsButton = $('<span class="button"><a href="tiki-view_tracker.php?trackerId=' + $trackerId + '">' + tr("Edit FutureLink Questions") + '</a></span>')
            .click(function() {
                var questionBox = $('<table style="width: 100%;" />');
                var questions = $questions;
                $.each(questions, function() {
                    $('<tr>')
                        .append('<td>' + this.Value + '</td>')
                        .append('<td title="' + tr("Edit") + '"><a class="edit" data-itemid="' + this.itemId + '"><img src="img/icons/pencil.png" /></a></td>')
                        .append('<td title="' + tr("Delete") + '"><a class="delete" data-itemid="' + this.itemId + '"><img src="img/icons/cross.png" /></a></td>')
                        .appendTo(questionBox);
                });

                questionBox.find('a.edit').click(function() {
                    var me = $(this);
                    var itemId = me.data('itemid');
                    trackerForm($trackerId, itemId, 'tracker_update_item', 'Question', function(frm) {

                        frm.find('span.trackerInput:not(.Value)').hide();

                        var dialogSettings = {
                            title: tr('Editing FutureLink Question: ') + me.parent().parent().text(),
                            modal: true,
                            buttons: {}
                        };

                        dialogSettings.buttons[tr('OK')] = function() {
                            frm.submit();
                        };

                        dialogSettings.buttons[tr('Cancel')] = function() {
                            questionDialog.dialog('close');
                        };

                        var questionDialog = $('<div />')
                            .append(frm)
                            .dialog(dialogSettings);
                    });

                    return false;
                });

                questionBox.find('a.delete').click(function() {
                    if (!confirm(tr("Are you sure?"))) return false;

                    var me = $(this);
                    var itemId = me.data('itemid');
                    trackerForm($trackerId, itemId, 'tracker_remove_item', 'Question', function(frm) {

                        frm.find('span.trackerInput:not(.Value)').hide();

                        frm.submit();
                    }, true);

                    return false;
                });

                var questionBoxOptions = {
                    title: tr("Edit FutureLink Questions"),
                        modal: true,
                        buttons: {}
                };
                questionBoxOptions.buttons[tr("New")] = function () {
                    trackerForm($trackerId, 0, 'tracker_insert_item', 'Question', function(frm) {

                        frm.find('span.trackerInput:not(.Value)').hide();

                        var newFrmDialogSettings = {
                            buttons: {},
                            modal: true,
                            title: tr('New')
                        };

                        newFrmDialogSettings.buttons[tr('Save')] = function() {
                            frm.submit();
                        };

                        newFrmDialogSettings.buttons[tr('Cancel')] = function() {
                            questionDialog.dialog('close');
                        };

                        var questionDialog = $('<div />')
                            .append(frm)
                            .dialog(newFrmDialogSettings);
                    });
                };
                questionBox.dialog(questionBoxOptions);
                return false;
            })
            .appendTo('#page-bar');
JQ
    );