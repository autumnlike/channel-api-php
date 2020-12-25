<?php
declare(strict_types=1);

namespace Lib\Client;

use Lib\Client\Base;

/**
 * UserChat APIs
 */
class UserChat extends Base
{
    /**
     * get user_chats by user_id
     *
     * @param string $userId
     */
    public function getUserChatsByUserId(string $userId): array
    {
        $uri = "users/{$userId}/user-chats";

        $body = $this->send('GET', $uri, [
            'headers' => $this->getHeaders(),
            'query' => [
                'limit' => 500, // 最大値
                'sortOrder' => 'desc',
            ],
        ]);

        return $body;
    }

    /**
     * trash user chat by user_chat_id
     *
     * @param string $userChatId
     */
    public function trashUserChatByUserChatId(string $userChatId)
    {
        $uri = "user-chats/{$userChatId}/trash";

        $body = $this->send('PATCH', $uri, [
            'headers' => $this->getHeaders(),
            'query' => [
                'botName' => $this->botName
            ]
        ]);

        return $body;
    }

    /**
     * destroy user chat by user_chat_id
     *
     * destroy は trash 済みでないと無効
     * destroy したら2度ともとに戻らない
     *
     * @param string $userChatId
     */
    public function destroyUserChatByUserChatId(string $userChatId)
    {
        $uri = "user-chats/{$userChatId}/destroy";

        $body = $this->send('DELETE', $uri, [
            'headers' => $this->getHeaders(),
        ]);

        return $body;
    }
}
