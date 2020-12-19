<?php
declare(strict_types=1);

namespace Lib\Client;

use Lib\Client\Base;

/**
 * Marketing APIs
 */
class Marketing extends Base
{
    /**
     * get users by campaign_id
     *
     * @param int $campaignId
     */
    public function getUsersByCampaignId(int $campaignId): array
    {
        $uri = "https://api.channel.io/open/v3/mkt/campaigns/{$campaignId}/campaign-users";

        $body = $this->send('GET', $uri, [
            'headers' => $this->getHeaders(),
            'query' => [
                'state' => 'sent',
                'limit' => 500, // 最大値
                'sortOrder' => 'desc',
            ],
        ]);

        return $body;
    }
}
