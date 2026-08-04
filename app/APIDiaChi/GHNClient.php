<?php

namespace App\APIDiaChi;

use Illuminate\Support\Facades\Http;

class GHNClient
{
    protected string $token;
    protected int $shopId;
    protected string $baseUrl = 'https://online-gateway.ghn.vn/shiip/public-api';

    public function __construct()
    {
        $this->token  = config('services.ghn.token');
        $this->shopId = (int) config('services.ghn.shop_id');

        if (!$this->token || !$this->shopId) {
            throw new \Exception('GHN config thiếu token hoặc shop_id');
        }
    }

    protected function request(string $method, string $uri, array $data = [])
    {
        $url = $this->baseUrl . $uri;

        $headers = [
            'Token'        => $this->token,
            'ShopId'       => $this->shopId,
            'Content-Type' => 'application/json',
        ];

        if (strtoupper($method) === 'GET') {
            $response = Http::withHeaders($headers)->get($url, $data);
        } else {
            $response = Http::withHeaders($headers)->post($url, $data);
        }

        // luôn trả về body JSON dạng mảng
        return $response->json();
    }

}
