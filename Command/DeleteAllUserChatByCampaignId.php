<?php
declare(strict_types=1);

namespace Command;

use Lib\Client\Marketing;
use Command\Task\UserChatTask;

/**
 * 自動配信IDをもとにユーザーへのチャットを削除するコマンド
 *
 * $ php ./Command/execute.php DeleteAllUserChatByCampaignId <配信ID>
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

        $next = null;
        $totalCount = 0;

        while(true) {

            $response = $marketingClient->getUsersByCampaignId($campaignId, 500, $next);
            $users = $response['campaignUsers'];

            if (!$users || !is_array($users)) {
                $this->out('対象データがありませんでした');
                exit;
            }

            $totalCount += count($users);
            $this->out($totalCount . '件取得完了');

            UserChatTask::deleteAll($users, $campaignId);

            $this->out($totalCount . '件削除完了');

            if (isset($response['next'])) {
                $next = $response['next'];
            } else {
                break;
            }
        }
    }
}
