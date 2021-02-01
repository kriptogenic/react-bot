<?php
require __DIR__ . '../Api.php';
require __DIR__ . '../NewChannelPostHandler.php';

$token = getenv('BOT_TOKEN');
$api = new Api($token);

$api->sendMessage(47543915, 'test');