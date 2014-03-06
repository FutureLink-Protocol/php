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
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
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

            var phrases = $('span.phrases'),
                phrasesLookupTable = {},
                show = function(table) {
                    $('<div>')
                        .append(table)
                        .dialog();
                };

            for(var x = 0; x < flpData.length; x++){
                if(!phrasesLookupTable[flpData[x].pastText.sanitized]){
                    phrasesLookupTable[flpData[x].pastText.sanitized] = [];
                }
                phrasesLookupTable[flpData[x].pastText.sanitized].push(flpData[x]);
            }


            for(var i = 0; i < flpData.length; i++) {
                var futureLink = new FutureLink(
                    phrases.filter('span.phraseBeginning' + i),
                    phrases.filter('span.phrase' + i),
                    phrases.filter('span.phraseEnd' + i),
                    counts[flpData[i].pastText.sanitized],
                    phrasesLookupTable[flpData[i].pastText.sanitized]
                );

                futureLink.show = show;
            }


		});
	</script>
</head>
<body>
<?php echo $ui->render();?>
<input type="button" id="button" value="Create PastLink"/>
</body>
</html>