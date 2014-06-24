<?php
require_once "../vendor/autoload.php";
$metadata = new FLP\Metadata();

$metadata->author = "Robert Plummer";
$metadata->authorInstitution = "Visual Interop Development llc";
$metadata->authorProfession = "Software Engineer";

$metadata->moderator = "Robert Plummer";
$metadata->moderatorInstitution = "Visual Interop Development llc";
$metadata->moderatorProfession = "Software Engineer";

$metadata->answers = array();
$metadata->categories = array();
$metadata->count = 0;
$metadata->dateLastUpdated = time();
$metadata->dateOriginated = time();
$metadata->href = "http://www.github.com/FutureLink-Protocol/php";
$metadata->keywords = array();
$metadata->language = "English";
$metadata->minimumMathNeeded = "";
$metadata->minimumStatisticsNeeded = "";
$metadata->scientificField = "";
$metadata->websiteTitle = "FutureLink-Protocol Demo";
$metadata->websiteSubtitle = "In php";



$title = 'The FutureLink-Protocol, An explanation';
$body = <<<Body
<p>One of my neatest projects is working with Abratech, creating the FutureLink-Protocol.
	@FLP(%7B%22websiteTitle%22%3A%22FutureLink-Protocol%20Demo%22%2C%22websiteSubtitle%22%3A%22In%20php%22%2C%22moderator%22%3A%22Robert%20Plummer%22%2C%22moderatorInstitution%22%3A%22Visual%20Interop%20Development%20llc%22%2C%22moderatorProfession%22%3A%22Software%20Engineer%22%2C%22hash%22%3A%22cf6beb29b1735773528298c4a24f5244%22%2C%22author%22%3A%22Don%20Jewett%22%2C%22authorInstitution%22%3A%22%22%2C%22authorProfession%22%3A%22Brain%20Scientist%22%2C%22href%22%3A%22http%3A%2F%2Flocalhost%2Fp%2Fflp-php%2Fdefault-ui%2F%22%2C%22answers%22%3A%5B%5D%2C%22dateLastUpdated%22%3A1384892798%2C%22dateOriginated%22%3A1384892798%2C%22language%22%3A%22English%22%2C%22count%22%3A0%2C%22keywords%22%3A%5B%5D%2C%22categories%22%3A%5B%5D%2C%22scientificField%22%3A%22%22%2C%22minimumMathNeeded%22%3A%22%22%2C%22minimumStatisticsNeeded%22%3A%22%22%2C%22text%22%3A%22an%20%5C%22enhanced%20Link%5C%22%20we'll%20call%20it%20a%20%5C%22PastLink%5C%22%2C%20and%20an%20%5C%22enhanced%20Backlink%5C%22%20will%20be%20called%20a%20%5C%22FutureLink%5C%22%22%7D)The FutureLink-Protocol is interesting as it creates a dynamic link, one that updates through time!@)
	But there are many other projects that I enjoy as well.
</p>
Body;

$simplePattern = '/[@]FLP([(].+[)])?(.+)?[@][)]/';
preg_match($simplePattern, $body, $match);
$clipboarddata = null;
$text = '';
if ($match) {
$clipboarddata = json_decode(urldecode(substr($match[1], 1, -1)));
$text = $match[2];

$body = preg_replace($simplePattern, $text, $body);
}
$metadata->text = $text;
$msg = '';

if (!($foundArticle = FLP\Data::getArticleByTitle($title))) {
	FLP\Data::createArticle($title, $body, $metadata);
	$msg = 'Article Created';
}

$pair = new FLP\Pair($clipboarddata, $metadata);
$assembled = new FLP\PairAssembler();
$assembled->futureText = new Phraser\Phrase($metadata->text);
$assembled->pastText = new Phraser\Phrase($clipboarddata->text);
$assembled->pair = $pair;
FLP\Pairs::add($pair);
FLP\SendToPast::send();
