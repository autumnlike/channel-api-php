<?php
declare(strict_types=1);

namespace Command;

use Lib\Client\Marketing;
use Lib\Client\UserChat;

/**
 * 配信IDをもとにユーザーへのチャットを削除するコマンド
 *
 * $ php Command/DeleteAllUserChatByCampaignId.php 1234<配信ID>
 */
final class DeleteAllUserChatByCampaignId extends BaseCommand
{
    /**
     * 実行関数
     */
    public function execute()
    {
        if (empty($this->params[0]) && !is_numeric($this->params[0])) {
            $this->out("Error: 配信IDを指定してください");
            $this->out();
            $this->out("  ex) `php Command/console.php <配信ID>");
            exit;
        }

        $campaignId = (int)$this->params[0];
        $marketingClient = new Marketing();

        try {
            $marketingClient->getUsersByOneTimeMessageId($campaignId, 1);
            $isCampaign = false;
        } catch (Exception $e) {
            $isCampaign = true;
        }

        $next = null;
        $totalCount = 0;

        while(true) {

            if ($isCampaign) {
                $response = $marketingClient->getUsersByCampaignId($campaignId, 500, $next);
                $users = $response['campaignUsers'];
            } else {
                $response = $marketingClient->getUsersByOneTimeMessageId($campaignId, 500, $next);
                $users = $response['oneTimeMsgUsers'];
            }

            if (!$users || !is_array($users)) {
                $this->out('対象データがありませんでした');
                exit;
            }

            $totalCount += count($users);
            $this->out($totalCount . '件取得完了');

            $this->deleteAll($users, $campaignId);

            $this->out($totalCount . '件削除完了');

            if (isset($response['next'])) {
                $next = $response['next'];
            } else {
                break;
            }
        }
    }

    /**
     * 指定ユーザーの配信を削除
     *
     * @param array $users
     * @param int $campaignId
     */
    function deleteAll(array $users, int $campaignId)
    {
        $userChatClient = new UserChat();
        foreach ($users as $user) {
            // ユーザーチャットIDを取得
            $userId = $user['userId'];

            $userChats = $userChatClient->getUserChatsByUserId($userId);
            $userChatId = $this->getUserChatIdBySourceId($userChats, $campaignId);

            // TODO 取得できなかった場合に次のページを取得する
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
     * @param array $userChats
     * @param int $campaignId
     */
    function getUserChatIdBySourceId(array $userChats, int $campaignId): ?string
    {
        foreach ($userChats['userChats'] as $userChat) {
            if ($userChat['sourceId'] == $campaignId) {
                return $userChat['id'];
            }
        }
        return null;
    }
}
//eval(\Psy\sh());
