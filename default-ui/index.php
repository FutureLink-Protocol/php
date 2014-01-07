<?php
require_once "receiver.php";
require_once "sender.php";
require_once "rb.php";
R::setup();
if (isset($_REQUEST['reset'])) {
    R::wipe( 'article' );
    R::wipe( 'pair' );
}