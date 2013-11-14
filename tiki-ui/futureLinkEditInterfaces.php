<?php
$perms = Perms::get();

//check if profile is created
// TODO: implement this or similar checks in search.php to explain why phrase query fails -- 2013-04-09 LDG
$trackerId = TikiLib::lib('trk')->get_tracker_by_name('Wiki Attributes');
if ($trackerId < 1 && $perms->admin == 'y') {
    TikiLib::lib('header')->add_jq_onready(
    <<<JQ
        var addQuestionsButton = $('<span class="button"><a href="tiki-admin.php?profile=Simple+Wiki+Attributes&repository=&page=profiles&list=List">' + tr('Apply Profile "Simple Wiki Attributes" To Add FutureLink Questions') + '</a></span>')
            .appendTo('#page-bar');
JQ
    );
} else {

    $trackerPerms = Perms::get(array( 'type' => 'tracker', 'object' => $trackerId ));
    $page = htmlspecialchars($this->page);

    if ($trackerPerms->edit == true) {
        TikiLib::lib('header')
            ->add_jsfile('lib/jquery_tiki/tiki-trackers.js')
            ->add_jq_onready(
                <<<JQ
                	function trackerForm(trackerId, itemId, tracker_fn_name, type, fn) {
                        $.modal(tr("Loading..."));

                        $.tracker_get_item_inputs({
                            trackerId: trackerId,
                            itemId: itemId,
                            byName: true,
                            defaults: {
                                Page: '$page',
                                Type: type
                            }
                        }, function(item) {
                            $.modal();

                            var frm = $('<form />')
                                .submit(function() {
                                    $.modal(tr('Saving...'));

                                    frm[tracker_fn_name]({
                                        trackerId: trackerId,
                                        itemId: itemId,
                                        byName: true
                                    }, function() {
                                        document.location = document.location + '';
                                    });

                                    return false;
                                });

                            for( field in item ) {
                                var input = $('<span />')
                                    .append(item[field])
                                    .addClass(field)
                                    .addClass('trackerInput')
                                    .appendTo(frm);
                            }

                            fn(frm);

                            $.modal();
                        });
                    }

                    function genericSingleTrackerItemInterface(type, item) {
                        var addButton = $('<span class="button"><a href="tiki-view_tracker.php?trackerId=' + $trackerId + '">' + tr("Edit FutureLink " + type) + '</a></span>')
                            .click(function() {
                                var box = $('<table style="width: 100%;" />');

                                $.each(item, function() {
                                    $('<tr>')
                                        .append('<td>' + this.Value + '</td>')
                                        .append('<td title="' + tr("Edit") + '"><a class="edit" data-itemid="' + this.itemId + '"><img src="img/icons/pencil.png" /></a></td>')
                                        .append('<td title="' + tr("Delete") + '"><a class="delete" data-itemid="' + this.itemId + '"><img src="img/icons/cross.png" /></a></td>')
                                        .appendTo(box);
                                });

                                box.find('a.edit').click(function() {
                                    var me = $(this);
                                    var itemId = me.data('itemid');
                                    trackerForm($trackerId, itemId, 'tracker_update_item', type, function(frm) {

                                        frm.find('span.trackerInput:not(.Value').hide();

                                        var dialogSettings = {
                                            title: tr('Editing FutureLink ' + type),
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

                                box.find('a.delete').click(function() {
                                    if (!confirm(tr("Are you sure?"))) return false;

                                    var me = $(this);
                                    var itemId = me.data('itemid');
                                    trackerForm($trackerId, itemId, 'tracker_remove_item', type, function(frm) {

                                        frm.find('span.trackerInput:not(.Value)').hide();

                                        frm.submit();
                                    }, true);

                                    return false;
                                });

                                box.options = {
                                    title: tr("Edit FutureLink " + type),
                                        modal: true,
                                        buttons: {}
                                };

                                if (item.length < 1) {
                                    box.options.buttons[tr("New")] = function () {
                                        trackerForm($trackerId, 0, 'tracker_insert_item', type, function(frm) {

                                            frm.find('span.trackerInput:not(.Value)').hide();

                                            var newFrmDialogSettings = {
                                                buttons: {},
                                                modal: true,
                                                title: tr('New ' + type)
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
                                }
                                box.dialog(box.options);
                                return false;
                            })
                            .appendTo('#page-bar');
                    }
JQ
            );

        $this->editQuestionsInterface($this->metadata->questions(), $trackerId);

        $keywords = json_encode($this->metadata->keywords(false));
        TikiLib::lib('header')->add_jq_onready("genericSingleTrackerItemInterface('Keywords', $keywords);");

        $scientificField = json_encode($this->metadata->scientificField(false));
        TikiLib::lib('header')->add_jq_onready("genericSingleTrackerItemInterface('Scientific Field', $scientificField);");

        $minimumMathNeeded = json_encode($this->metadata->minimumMathNeeded(false));
        TikiLib::lib('header')->add_jq_onready("genericSingleTrackerItemInterface('Minimum Math Needed', $minimumMathNeeded);");

        $minimumStatisticsNeeded = json_encode($this->metadata->minimumStatisticsNeeded(false));
        TikiLib::lib('header')->add_jq_onready("genericSingleTrackerItemInterface('Minimum Statistics Needed', $minimumStatisticsNeeded);");
    }
}