<?php

class InlineButtonHandler
{
    public function __construct(private Api $api){}

    public function handle(stdClass $callback_query)
    {
        if (str_starts_with($callback_query->data, 'up_')) {
            if ($callback_query->data === 'up_active') {
                $keyboard = ['up_' => '🔹', 'down_' => '🔸'];
            } else {
                $keyboard = ['up_active' => '[🔹]', 'down_' => '🔸'];
            }
        } elseif (str_starts_with($callback_query->data, 'down_')) {
            if ($callback_query->data === 'down_active') {
                $keyboard = ['up_' => '🔹', 'down_' => '🔸'];
            } else {
                $keyboard = ['up_' => '🔹', 'down_active' => '[🔸]'];
            }
        } else {
            return;
        }

        $this->api->editMessageReplyMarkup($callback_query->message->chat->id,
            $callback_query->message->message_id,
            reply_markup: $this->api->callbackKeyboard($keyboard));
    }
}
