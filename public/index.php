<?php

$token = getenv('BOT_TOKEN');

$api = new Api($token);

$api->sendMessage(47543915, 'test');