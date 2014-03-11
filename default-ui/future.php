<?php
	require_once "future.setup.php";
?><!DOCTYPE html>
<html>
<head>
	<title>FutureLink-Protocol, Demo of Future Article linking to a PastLink</title>
</head>
<body><?php
$ui = new FLP\UI($body);

$ui->addPhrase(new Phraser\Phrase($text));

echo $ui->render();?>
</body>
<script src="../vendor/jquery/jquery/jquery-1.10.2.js"></script>
<script src="../scripts/flp.js"></script>
<script src="../scripts/flp.Link.js"></script>
<link rel="stylesheet" href="../vendor/jquery/jquery-ui/themes/base/jquery-ui.css" />
<script>
	var flpData = <?php echo json_encode(array($assembled));?>,
        phrases = $('span.phrases');

	(new flp.Link({
        beginning: phrases.filter('span.phraseBeginning0'),
        middle: phrases.filter('span.phrase0'),
        end: phrases.filter('span.phraseEnd0'),
	    to: 'past',
	    pairs: flpData
    })).show = function(el) {
		$('body').append(el);
	};
</script>
</html>