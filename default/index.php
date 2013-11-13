<?php

FutureLink\Events::bind(new FutureLink\Event\Send(function($url, $params, &$result, &$item, &$items) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
}));