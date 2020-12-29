<?php
declare(strict_types=1);

namespace Command\Task;

use Lib\Client\UserChat;

final class UserChatTask
{
    /**
     * 指定ユーザーの配信を削除
     *
     * @param array $users
     * @param int $sourceId
     */
    public static function deleteAll(array $users, int $sourceId)
    {
        foreach ($users as $user) {
            $userChatId = self::getUserChatIdByUserIdAndSourceId($user['userId'], $sourceId);

            // 対象なしは削除済み
            if (is_null($userChatId)) {
                continue;
            }

            // ユーザーチャットを削除
            $userChatClient->trashUserChatByUserChatId($userChatId);
        }
    }

    /**
     * レスポンス配列からUserChatIdを取得
     *
     * @param string $userId
     * @param int $sourceId
     */
    private static function getUserChatIdByUserIdAndSourceId(string $userId, int $sourceId): ?string
    {
        $userChatClient = new UserChat();
        $next = null;

        while (true) {
            $response = $userChatClient->getUserChatsByUserId($userId, $next);
            if (empty($userChats['userChats'])) {
                return null;
            }
            foreach ($userChats['userChats'] as $userChat) {
                if ($userChat['sourceId'] == $sourceId) {
                    return $userChat['id'];
                }
            }
            if (isset($response['next'])) {
                $next = $response['next'];
            } else {
                break;
            }
        }
        return null;
    }
}
