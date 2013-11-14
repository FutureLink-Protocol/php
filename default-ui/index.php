<?php

require_once "../autoload.php";

$metadata = new FLP\Metadata();

$metadata->author = "Don Jewett";
$metadata->answers = array();
$metadata->authorInstitution = "";
$metadata->authorProfession = "Brain Scientist";
$metadata->categories = array();
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

$clipboarddata = json_encode($metadata);
?><html>
<head>
    <script src="../jquery.md5.js"></script>
    <script src="../Phraser/rangy/rangy-core.js"></script>
    <script src="../Phraser/rangy/rangy-textrange.js"></script>
    <script src="../Phraser/rangy-phraser.js"></script>
    <script>
        var clipboarddata = '<?php echo $clipboarddata ?>;';

        function createPastLink(text) {

        }
    </script>
</head>
<body>
    <p>We want to take the present-day web-functionality of "link" and "backlink" and enhance it for use by scholars. So when we talk of an "enhanced Link" we'll call it a "PastLink", and an "enhanced Backlink" will be called a "FutureLink".</p>

    <p>The term Backlink is very confusing because a Backlink points forwards in time (see Fig. below in Section 2.3).</p>

    <p>The difference in terminology between a FutureLink and a Backlink is the difference in viewpoint between an Author and a Reader:</p>

    <p>An Author creates a Link from a newer article to an older one, and thus can imagine a Backlink from the older article pointing "back" to the Author's newer article. NB: "Back" here does not mean "backwards in time", it means "back to an origin".</p>

    <p>A Reader of the older article sees the link to a newer article as a "FutureLink" (Forwards-in-time), and is confused if it is called a "Backlink". NB: "Forward" here means "Forward-in-time".</p>

    <input type="button" value="Create PastLink" onclick="createPastLink(this.value);return false;"/>
</body>
</html>

