<?php

class NewChannelPostHandler
{
    public function __construct(private Api $api){}

    public function handle(stdClass $channel_post) {
        $keyboard = $this->api->callbackKeyboard(['up_' => '🔹', 'down_' => '🔸']);
        $this->api->sendMessage($channel_post->chat->id, '<code>=================</code>',
            parse_mode: 'HTML', reply_to_message_id: $channel_post->message_id, reply_markup: $keyboard);
    }
}
