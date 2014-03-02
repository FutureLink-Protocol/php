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
<script src="../jquery-1.10.2.min.js"></script>
<script src="../scripts/FutureLink.js"></script>
<script>
    var phrases = $('span.phrases');

    new FutureLink(
        phrases.filter('span.phraseBeginning0'),
        phrases.filter('span.phrase0'),
        phrases.filter('span.phraseEnd0')
    );
</script>
</html>