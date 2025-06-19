<?php
class ZoomTokenManager {
    private $client_id = '6PODjEJYRjaWnZ7uXqnT9Q';
    private $client_secret = 'PA4SqhDYrjgDZWTmkfICXugrkQltbmuY';
    private $token_file = __DIR__.'/access_token.json';

    public function getAccessToken() {
        if (!file_exists($this->token_file)) {
            throw new Exception("Token dosyası bulunamadı, OAuth akışını tamamlayın.");
        }

        $tokenData = json_decode(file_get_contents($this->token_file), true);

        if (time() > $tokenData['expires_at']) {
            $tokenData = $this->refreshToken($tokenData['refresh_token']);
        }
        return $tokenData['access_token'];
    }

    private function refreshToken($refresh_token) {
        $url = 'https://zoom.us/oauth/token';
        $headers = [
            'Authorization: Basic ' . base64_encode("{$this->client_id}:{$this->client_secret}"),
            'Content-Type: application/x-www-form-urlencoded',
        ];
        $postFields = http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['access_token'])) {
            $data['expires_at'] = time() + $data['expires_in'] - 60;
            file_put_contents($this->token_file, json_encode($data));
            return $data;
        }

        throw new Exception("Access token yenilenemedi: " . $response);
    }
}
