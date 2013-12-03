<?php
require_once "receiver.php";
require_once "sender.php";
require_once "rb.php";
R::setup();
R::wipe( 'article' );
R::wipe( 'pair' );