<?php
	require_once "past.setup.php";
?><!DOCTYPE html><html>
<head>
	<title>The FutureLink-Protocol <?php echo (empty($msg) ? '' : '(' . $msg . ')')?></title>
	<script src="../jquery-1.10.2.min.js"></script>
	<script src="../md5.min.js"></script>
	<script src="../Phraser/rangy/rangy-core.js"></script>
	<script src="../Phraser/rangy/rangy-textrange.js"></script>
	<script src="../Phraser/rangy-phraser.js"></script>
	<script src="../scripts/FutureLink.js"></script>
	<script src="../scripts/PastLink.js"></script>
	<script>
        var flpData = <?php echo json_encode($pairs);?>,
            counts = <?php echo json_encode(FLP\PairAssembler::$counts) ?>,
            incompleteData = <?php echo $incompleteData ?>;

		$(function() {
			$('#button').click(function() {
                var pastLink = new PastLink(incompleteData);

                console.log(pastLink);

				prompt(
                    'Here is your clipboard data',
                    pastLink.toClipBoardData()
                );
				return false;
			});

            var phrases = $('span.phrases');

            for(var i = 0; i <= flpData.length; i++) {
                new FutureLink(
                    phrases.filter('span.phraseBeginning' + i),
                    phrases.filter('span.phrase' + i),
                    phrases.filter('span.phraseEnd' + i),
                    counts[flpData[i].pastText.sanitized]
                );
            }
		});

	</script>
</head>
<body>
<?php echo $ui->render();?>
<input type="button" id="button" value="Create PastLink"/>
</body>
</html>