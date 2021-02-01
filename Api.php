<?php


class Api
{
    private string $api_endpoit;

    public function __construct(private string $token)
    {
        $this->api_endpoit = 'https://api.telegram.org/bot' . $this->token . '/';
    }

    private function request(string $method, array $params = null) :stdClass
    {
        $ch = curl_init($this->api_endpoit . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (isset($params)) {
            $params = $this->clearNullValues($params);
            curl_setopt_array($ch,[
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $params
            ]);
        }

        $response = json_decode(curl_exec($ch), flags: JSON_THROW_ON_ERROR);
        curl_close ($ch);

        if ($response->ok === false) {
            throw new Exception('API Error:' . json_encode([
                    'response' =>$response,
                    'method' => $method,
                    'params' => $params
            ]));
        }

        return $response;
    }

    private function clearNullValues(array $values) :array
    {
        return array_filter($values, fn($value) => !is_null($value));
    }

    public function sendMessage(
        int|string $chat_id, string $text, string $parse_mode = null, array $entities = null,
        bool $disable_web_page_preview = null, bool $disable_notification = null,
        int $reply_to_message_id = null, bool $allow_sending_without_reply = null,
        array $reply_markup = null
    ) :stdClass
    {
        return $this->request('sendMessage', compact(
        'chat_id', 'text', 'parse_mode',
                'entities', 'disable_web_page_preview', 'disable_notification', 'reply_to_message_id',
                'allow_sending_without_reply', 'reply_markup'
        ));
    }
}