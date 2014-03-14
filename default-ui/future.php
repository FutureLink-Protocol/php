<?php
	require_once "future.setup.php";
?><!DOCTYPE html>
<html>
<head>
	<title>FutureLink-Protocol, Demo of Future Article linking to a PastLink</title>
</head>
<body><?php
$ui = new FLP\UI($body);
$ui->setContextAsFuture();
$ui->addPhrase(new Phraser\Phrase($text));

echo $ui->render();?>
</body>
<script src="../vendor/jquery/jquery/jquery-1.10.2.js"></script>
<script src="../scripts/flp.js"></script>
<script src="../scripts/flp.Link.js"></script>
<script type="text/javascript" src="../tablesorter/js/jquery.tablesorter.js"></script>
<link rel="stylesheet" href="../tablesorter/css/theme.dropbox.css">
<link rel="stylesheet" href="../vendor/jquery/jquery-ui/themes/base/jquery-ui.css" />
<script>
	var flpData = <?php echo json_encode(array($assembled));?>,
        phrases = $('span.phrases');

	(new flp.Link({
        beginning: phrases.filter('span.pastlink-beginning0'),
        middle: phrases.filter('span.pastlink0'),
        end: phrases.filter('span.pastlink-end0'),
	    to: 'past',
	    pairs: flpData
    })).show = function(el) {
        $(el)
            .appendTo('body')
            .addClass('tablesorter tablesorter-dropbox')
            .tablesorter();
	};
</script>
</html>