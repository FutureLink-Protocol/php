<?php
require_once "../vendor/autoload.php";

$metadata = new FLP\Metadata();

$metadata->author = "Don Jewett";
$metadata->authorInstitution = "";
$metadata->authorProfession = "Brain Scientist";

$metadata->moderator = "Robert Plummer";
$metadata->moderatorInstitution = "Visual Interop Development llc";
$metadata->moderatorProfession = "Software Engineer";

$metadata->answers = array();
$metadata->categories = array();
$metadata->count = 0;
$metadata->dateLastUpdated = time();
$metadata->dateOriginated = time();
$metadata->href = "http://localhost/p/flp-php/default-ui/index.php";
$metadata->keywords = array();
$metadata->language = "English";
$metadata->minimumMathNeeded = "";
$metadata->minimumStatisticsNeeded = "";
$metadata->scientificField = "";
$metadata->websiteTitle = "FutureLink-Protocol Demo";
$metadata->websiteSubtitle = "In php";


$incompleteData = json_encode($metadata);
$title = 'The FutureLink-Protocol';
$body = <<<Body
<p>We want to take the present-day web-functionality of "link" and "backlink" and enhance it for use by scholars. So when we talk of an "enhanced Link" we'll call it a "PastLink", and an "enhanced Backlink" will be called a "FutureLink".</p>

<p>The term Backlink is very confusing because a Backlink points forwards in time (see Fig. below in Section 2.3).</p>

<p>The difference in terminology between a FutureLink and a Backlink is the difference in viewpoint between an Author and a Reader:</p>

<p>An Author creates a Link from a newer article to an older one, and thus can imagine a Backlink from the older article pointing "back" to the Author's newer article. NB: "Back" here does not mean "backwards in time", it means "back to an origin".</p>

<p>A Reader of the older article sees the link to a newer article as a "FutureLink" (Forwards-in-time), and is confused if it is called a "Backlink". NB: "Forward" here means "Forward-in-time".</p>
Body;
$msg = '';

if (!($foundArticle = FLP\Data::getArticleByTitle($title))) {
	FLP\Data::createArticle($title, $body, $metadata);
	$msg = 'Article Created';
}

$ui = new FLP\UI($body);
$ui->setContextAsPast();
$pairs = FLP\Data::GetPairsByTitleAndApplyToUI($title, $ui);