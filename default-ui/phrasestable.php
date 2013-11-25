<?php

function drawTable(FLP\UI $ui)
{
	$table =
		'<table>' .
			drawHeader() .
			'<tbody>';

	$pairsGrouped = array();
	foreach(FLP\Pairs::$pairs as $pair)
	{
		if (!isset($pairsGrouped[$pair->past->text])) {
			$pairsGrouped[$pair->past->text] = array();
		}

		$pairsGrouped[$pair->past->text] = $pair;
	}

	foreach($pairsGrouped as $pair) {
		$table .= drawRow($pair->past);
	}

	$table .=
			'</tbody>' .
		'</table>';

	return $table;
}

function drawHeader()
{
	return '<tr>' .
		'<th>Text</th>' .
		'<th>Site</th>' .
	'</tr>';
}

function drawRow(FLP\Metadata $metadata)
{
	return '<tr>' .
		'<td>' . strip_tags($metadata->text) . '</td>' .
		'<td>' . strip_tags($metadata->href) . '</td>' .
	'</tr>';
}

drawTable($ui);
?>
<script>
	$('th').click(function(){
		var table = $(this).parents('table').eq(0),
			rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
		this.asc = !this.asc;
		if (!this.asc){rows = rows.reverse()}
		for (var i = 0; i < rows.length; i++){table.append(rows[i])}
	})
	var numberRegex = /^-?\d+\.?\d*$/;
	function comparer(index) {
		return function(a, b) {
			var valA = getCellValue(a, index), valB = getCellValue(b, index);
			return (isNumber(valA) && isNumber(valB)) ? valA - valB : valA.localeCompare(valB);
		};
	}
	function getCellValue(row, index){ return $(row).children('td').eq(index).html(); }
	function isNumber(value){ return (value != null && value.match(numberRegex) != null); }
</script>