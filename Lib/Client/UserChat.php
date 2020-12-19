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
        $uri = "https://api.channel.io/open/v3/users/{$userId}/user-chats";

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
     * destroy user chat by user_chat_id
     *
     * @param string $userChatId
     */
    public function destroyUserChatByUserChatId(string $userChatId)
    {
        $uri = "https://api.channel.io/open/v3/user-chats/{$userChatId}/destroy";

        $body = $this->send('DELETE', $uri, [
            'headers' => $this->getHeaders(),
        ]);

        return $body;
    }
}
