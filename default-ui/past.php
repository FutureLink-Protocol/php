<?php
	require_once "past.setup.php";
?><!DOCTYPE html><html>
<head>
	<title>The FutureLink-Protocol <?php echo (empty($msg) ? '' : '(' . $msg . ')')?></title>
	<script src="../vendor/jquery/jquery/jquery-1.10.2.js"></script>
	<script src="../vendor/md5/md5/js/md5.js"></script>
	<script src="../vendor/rangy/rangy/rangy-core.js"></script>
	<script src="../vendor/rangy/rangy/rangy-textrange.js"></script>
	<script src="../Phraser/rangy-phraser.js"></script>
	<script src="../scripts/flp.js"></script>
	<script src="../scripts/flp.Link.js"></script>
	<script src="../scripts/flp.PastLinkCreator.js"></script>
    <link rel="stylesheet" href="../vendor/jquery/jquery-ui/themes/base/jquery-ui.css" />
	<script>
        var flpData = <?php echo json_encode($pairs);?>,
            counts = <?php echo json_encode(FLP\PairAssembler::$counts) ?>,
            incompleteData = <?php echo $incompleteData ?>;

		$(function() {
			$('#button').click(function() {
                var pastLink = new flp.PastLinkCreator(incompleteData);

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
                    $('body')
                        .append(table);
                };

            for(var x = 0; x < flpData.length; x++){
                if(!phrasesLookupTable[flpData[x].pastText.sanitized]){
                    phrasesLookupTable[flpData[x].pastText.sanitized] = [];
                }
                phrasesLookupTable[flpData[x].pastText.sanitized].push(flpData[x]);
            }


            for(var i = 0; i < flpData.length; i++) {
                var futureLink = new flp.Link({
                    beginning: phrases.filter('span.phraseBeginning' + i),
                    middle: phrases.filter('span.phrase' + i),
                    end: phrases.filter('span.phraseEnd' + i),
                    count: counts[flpData[i].pastText.sanitized],
                    pairs: phrasesLookupTable[flpData[i].pastText.sanitized]
                });

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