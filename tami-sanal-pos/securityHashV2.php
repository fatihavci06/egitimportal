<?php
require 'lib/JWKResource.php';

class JWTSignatureGenerator {

    private static function generateKidValue(string $secretKey, string $fixedKidValue): string {
        $input = $secretKey . $fixedKidValue;
        return base64_encode(hash('sha512', $input, true));
    }

    private static function generateKValue(int $merchantNumber, int $terminalNumber, string $secretKey, string $fixedKValue): string {
        $input = $secretKey . $fixedKValue . $merchantNumber . $terminalNumber;
        return base64_encode(hash('sha512', $input, true));
    }

    private static function getJWKResource(int $merchantNumber, int $terminalNumber, string $secretKey, string $fixedKidValue, string $fixedKValue): JWKResource {
        $jwkResource = new JWKResource();
        $jwkResource->setKid(self::generateKidValue($secretKey, $fixedKidValue));
        $jwkResource->setK(self::generateKValue($merchantNumber, $terminalNumber, $secretKey, $fixedKValue));
        return $jwkResource;
    }

    private static function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function generateJWKSignature(int $merchantNumber, int $terminalNumber, string $secretKey, string $input, string $fixedKidValue, string $fixedKValue): string {
        $jwkResource = self::getJWKResource($merchantNumber, $terminalNumber, $secretKey, $fixedKidValue, $fixedKValue);

        // Create header
        $header = [
            'kid' => $jwkResource->getKid(),
            'typ' => 'JWT',
            'alg' => 'HS512'
        ];

        // Encode header and payload
        $headerEncoded = self::base64UrlEncode(json_encode($header));
        $payloadEncoded = self::base64UrlEncode($input);

        // Create signature
        $dataToSign = $headerEncoded . '.' . $payloadEncoded;
        $signatureKey = base64_decode($jwkResource->getK());
        $signature = hash_hmac('sha512', $dataToSign, $signatureKey, true);
        $signatureEncoded = self::base64UrlEncode($signature);

        // Combine all parts
        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }
}
?>
