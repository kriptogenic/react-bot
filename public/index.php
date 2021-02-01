<?php

require __DIR__ . '/../Api.php';
require __DIR__ . '/../NewChannelPostHandler.php';
require __DIR__ . '/../InlineButtonHandler.php';

$channel = getenv('CHANNEL_ID');
$webhook_secret = getenv('WEBHOOK_SECRET');
$api = new Api();

if (empty($_GET['ws']) || $_GET['ws'] !== $webhook_secret) {
    $api->forbidden();
}



// Fetching update
try {
    $update = $api->getUpdate();
} catch (JsonException){
    $api->forbidden();
}

// Routing
if (isset($update->channel_post)) {
    if ($update->channel_post->chat->id == $channel) {
        (new NewChannelPostHandler($api))
            ->handle($update->channel_post);
    } else {
        // Leaving from other channels
        $api->leaveChat($update->channel_post->chat->id);
    }
} elseif (isset($update->callback_query)) {
    if (isset($update->callback_query->message) && $update->callback_query->message->chat->id == $channel) {
        (new InlineButtonHandler($api))
            ->handle($update->callback_query);
    }
} elseif (isset($update->message) && in_array($update->message->chat->type, ['group', 'supergroup'])) {
    // Leaving from groups
    $api->leaveChat($update->message->chat->id);
}


$api->executeResponseApiCall();