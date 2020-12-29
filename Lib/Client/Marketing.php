<?php
declare(strict_types=1);

namespace Lib\Client;

use Lib\Client\Base;

/**
 * Marketing APIs
 */
final class Marketing extends Base
{
    /**
     * get users by campaign_id
     *
     * @param int $campaignId
     */
    public function getUsersByCampaignId(int $campaignId, int $limit = 500, ?string $next = null): array
    {
        $uri = "mkt/campaigns/{$campaignId}/campaign-users";

        $body = $this->send('GET', $uri, [
            'headers' => $this->getHeaders(),
            'query' => [
                'state' => 'sent',
                'limit' => $limit,
                'sortOrder' => 'desc',
                'since' => $next
            ],
        ]);

        return $body;
    }

    /**
     * get users by one_time_message_id
     *
     * @param int $messageId
     */
    public function getUsersByOneTimeMessageId(int $messageId, int $limit = 500, ?string $next = null): array
    {
        $uri = "mkt/one-time-msgs/{$messageId}/one-time-msg-users";

        $body = $this->send('GET', $uri, [
            'headers' => $this->getHeaders(),
            'query' => [
                'state' => 'sent',
                'limit' => $limit,
                'sortOrder' => 'desc',
                'since' => $next
            ],
        ]);

        return $body;
    }
}
