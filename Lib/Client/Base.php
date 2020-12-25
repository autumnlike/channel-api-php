<?php
declare(strict_types=1);

namespace Lib\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Base Class
 */
class Base
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $accessKey;

    /**
     * @var string
     */
    private $accessSecret;

    /**
     * @var string
     */
    protected $rootUri = 'https://api.channel.io/open/v3/';

    /**
     * botName パラメータに設定する値
     *
     * FIXME 動的に設定したい場合はこれを `$_ENV` にする
     *
     * @var string
     */
    protected $botName = 'channel-api-php';

    /**
     * construct
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->rootUri,
        ]);

        // .env での設定が必須
        $this->accessKey = $_ENV['CHANNEL_ACCESS_KEY'];
        $this->accessSecret = $_ENV['CHANNEL_ACCESS_SECRET'];
    }

    /**
     * リクエストヘッダー
     */
    protected function getHeaders(): array
    {
        return [
            'accept' => 'application/json',
            'x-access-key' => $this->accessKey,
            'x-access-secret' => $this->accessSecret,
        ];
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $option
     * @return array
     * @throws FreenanceException
     */
    protected function send(string $method, string $uri, array $option)
    {
        try {

            $response = $this->client->request($method, $uri, $option);
            $responseBody = json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            // FIXME エラーレスポンスを正しく返したい
            throw new \Exception('failed to send:' . $e->getMessage() . ', uri: ' . $e->getRequest()->getUri() . ', status: ' . $e->getCode() . ', option: ' . json_encode($option) . ', response_body: ' .  \GuzzleHttp\Psr7\str($e->getResponse()));
        }
        return $responseBody;
    }
}
