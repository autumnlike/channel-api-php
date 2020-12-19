<?php
declare(strict_types=1);

/**
 * 配信IDをもとにユーザーへのチャットを削除するコマンド
 *
 * $ php Command/DeleteAllUserChatByCampaignId.php 1234<配信ID>
 */

require dirname(__FILE__) . '/../dispatcher.php';

use Lib\Client\Marketing;
use Lib\Client\UserChat;

if (empty($argv[1]) && !is_numeric($argv[1])) {
    echo "Error: 配信IDを指定してください\n\n";
    echo "  ex) `php Command/console.php <配信ID>`\n\n";
    exit;
}

$marketingClient = new Marketing();
$campaignId = (int)$argv[1];

try {
    $marketingClient->getUsersByCampaignId($campaignId, 1);
    $isCampaign = true;
} catch (Exception $e) {
    $isCampaign = false;
}

$next = null;
// ユーザー一覧を取得
while(true) {

    if ($isCampaign) {
        $users = current($marketingClient->getUsersByCampaignId($campaignId, 500, $next));
    } else {
        $users = current($marketingClient->getUsersByOneTimeMessageId($campaignId, 500, $next));
    }

    deleteAll($users, $campaignId);

    if (isset($users['next'])) {
        $next = $users['next'];
    } else {
        break;
    }
}

function deleteAll(array $users, int $campaignId)
{
    $userChatClient = new UserChat();
    foreach ($users as $user) {
        // ユーザーチャットIDを取得
        $userId = $user['userId'];
        $userChats = $userChatClient->getUserChatsByUserId($userId);
        $userChatId = getUserChatIdBySourceId($userChats, $campaignId);

        // ユーザーチャットを削除
        $userChatClient->destroyUserChatByUserChatId($userChatId);
    }
}

function getUserChatIdBySourceId(array $userChats, int $campaignId): ?string
{
    foreach ($userChats['userChats'] as $userChat) {
        if ($userChat['sourceId'] == $campaignId) {
            return $userChat['id'];
        }
    }
    return null;
}

eval(\Psy\sh());
