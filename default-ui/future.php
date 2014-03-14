<?php
	require_once "future.setup.php";
?><!DOCTYPE html>
<html>
<head>
	<title>FutureLink-Protocol, Demo of Future Article linking to a PastLink</title>
    <link rel="stylesheet" href="../vendor/tablesorter/tablesorter/css/theme.dropbox.css"/>
    <link rel="stylesheet" href="../vendor/jquery/jquery-ui/themes/base/jquery-ui.css" />
</head>
<body><?php
$ui = new FLP\UI($body);
$ui->setContextAsFuture();
$ui->addPhrase(new Phraser\Phrase($text));

echo $ui->render();?>
</body>
<script src="../vendor/jquery/jquery/jquery-1.10.2.js"></script>
<script src="../vendor/rangy/rangy/uncompressed/rangy-core.js"></script>
<script src="../vendor/rangy/rangy/uncompressed/rangy-textrange.js"></script>
<script src="../Phraser/rangy-phraser.js"></script>
<script src="../scripts/flp.js"></script>
<script src="../scripts/flp.Link.js"></script>
<script src="../vendor/tablesorter/tablesorter/js/jquery.tablesorter.js"></script>
<script>
	var flpData = <?php echo json_encode(array($assembled));?>,
        phrases = $('span.phrases');

    var beginning = phrases.filter('span.pastlink-beginning0'),
        middle = phrases.filter('span.pastlink0'),
        end = phrases.filter('span.pastlink-end0'),
        link = new flp.Link({
            beginning: beginning,
            middle: middle,
            end: end,
            to: 'past',
            pairs: flpData
        });

    link.show = function(el) {
        $(el)
            .appendTo('body')
            .addClass('tablesorter tablesorter-dropbox')
            .tablesorter();
	};

    $(function() {
        flp.addPastLink(link);
        setTimeout(function() {
            flp.selectAndScrollToPastLink(link.settings.pairs[0].futureText.sanitized);
        }, 50);
    });
</script>
</html>