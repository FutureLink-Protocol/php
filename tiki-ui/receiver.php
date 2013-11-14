<?php
if (isset($_POST['protocol']) && $_POST['protocol'] == 'futurelink' && isset($_POST['metadata'])) {
    $receive = new FLP\ReceiveFromPast($args['object']);
    $futureLink = new FLP\FutureLink($args['object']);

    //here we do the confirmation that another wiki is trying to talk with this one
    $metadata = json_decode($_POST['metadata']);
    $metadata->origin = $_POST['REMOTE_ADDR'];

    if ($futureLink->addItem($metadata) == true) {
        $receive->response = 'success';
    } else {
        $receive->response = 'failure';
    }

    $feed = $receive->feed($_SERVER['REQUEST_URI']);

    if (
        $receive->response == 'failure' &&
        $futureLink == true
    ) {
        $feed->reason = $futureLink->security->verifications;
    }

    echo json_encode($feed);
    exit();
}