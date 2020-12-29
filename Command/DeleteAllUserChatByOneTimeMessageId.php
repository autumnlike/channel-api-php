<?php
declare(strict_types=1);

namespace Command;

use Lib\Client\Marketing;
use Command\Task\UserChatTask;

/**
 * 一斉配信IDをもとにユーザーへのチャットを削除するコマンド
 *
 * $ php ./Command/execute.php DeleteAllUserChatByOneTimeMessageId <配信ID>
 */
final class DeleteAllUserChatByOneTimeMessageId extends BaseCommand
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

        $oneTimeMessageId = (int)$this->params[0];
        $marketingClient = new Marketing();

        $next = null;
        $totalCount = 0;

        while(true) {

            $response = $marketingClient->getUsersByOneTimeMessageId($oneTimeMessageId, 500, $next);
            $users = $response['oneTimeMsgUsers'];

            if (!$users || !is_array($users)) {
                $this->out('対象データがありませんでした');
                exit;
            }

            $totalCount += count($users);
            $this->out($totalCount . '件取得完了');

            UserChatTask::deleteAll($users, $oneTimeMessageId);

            $this->out($totalCount . '件削除完了');

            if (isset($response['next'])) {
                $next = $response['next'];
            } else {
                break;
            }
        }
    }
}
