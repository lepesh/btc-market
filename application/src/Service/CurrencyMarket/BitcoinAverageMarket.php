<?php

declare(strict_types=1);

namespace App\Service\CurrencyMarket;

use DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BitcoinAverageMarket implements CurrencyMarket
{
    private const BASE_URL = 'https://apiv2.bitcoinaverage.com';
    private const RESOLUTION_HOUR = 'hour';

    private HttpClientInterface $client;
    private array $requestOptions;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->requestOptions = [
            'headers' => [
                'x-ba-key' => $apiKey
            ]
        ];
    }

    /**
     * @param string $symbol
     * @param DateTime|null $startDate
     * @return array|HistoryDTO[]
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function history(string $symbol, DateTime $startDate = null): array
    {
        if (!$this->symbolSupported($symbol)) {
            return [];
        }
        $params['resolution'] = self::RESOLUTION_HOUR;
        if ($startDate) {
            $params['since'] = $startDate->getTimestamp();
        }
        $url = sprintf('%s/%s/%s?%s',
            self::BASE_URL,
            'indices/global/history',
            $symbol,
            http_build_query($params)
        );

        $response = $this->client->request('GET', $url, $this->requestOptions);
        $ticks = array_reverse($response->toArray());
        $result = [];
        foreach ($ticks as $tick) {
            $result[] = new HistoryDTO($tick['average'], new DateTime($tick['time']));
        }

        return $result;
    }

    public function symbolSupported(string $symbol): bool
    {
        $url = self::BASE_URL . '/info/indices/history/global';
        $response = $this->client->request('GET', $url, $this->requestOptions);
        $result = $response->toArray();

        return isset($result['symbols']) && in_array($symbol, $result['symbols']);
    }
}
