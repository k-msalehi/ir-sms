<?php

namespace KMsalehi\IrSms\Gateways;

use KMsalehi\IrSms\Config\Config;
use KMsalehi\IrSms\Contracts\PatternSend;
use KMsalehi\IrSms\Exceptions\SmsException;

// kavenegar.ir gateway
class Kavenegar implements PatternSend
{
    protected $apiKey;
    protected string $apiUrl = 'https://api.kavenegar.com/v1/{apikey}/verify/lookup.json';
    protected string $apiPatternUrl = 'https://api.kavenegar.com/v1/{apikey}/verify/lookup.json';
    protected $from;
    protected $to;
    protected $debug;


    public function __construct(?string $to = null, ?string $from = null)
    {
        $this->from = $from;
        $this->to = $to;

        $config = new Config();
        $this->apiKey = $config->getKey('kavenegar');
        $this->debug = $config->get('debug');

        $this->apiPatternUrl = str_replace('{apikey}', $this->apiKey, $this->apiPatternUrl);
        $this->apiUrl = str_replace('{apikey}', $this->apiKey, $this->apiUrl);
    }

    public function sendByPattern(string $patternId,  array $inputs, string $to, ?string $from = null)
    {
        $token = array_column($inputs, 'token');
        $token = $token ? $token[0] : null;

        $token2 = array_column($inputs, 'token2');
        $token2 = $token2 ? $token2[0] : null;

        $token3 = array_column($inputs, 'token3');
        $token3 = $token3 ? $token3[0] : null;

        $data = [
            'receptor' => $to,
            'token' => $token,
            'template' => $patternId,
        ];

        if (isset($token2)) {
            $data['token2'] = $token2;
        }
        if (isset($token3)) {
            $data['token3'] = $token3;
        }

        $query = http_build_query($data);

        $this->apiPatternUrl .= '?' . $query;

        $ch = curl_init($this->apiPatternUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Get response as a string
        curl_setopt($ch, CURLOPT_TIMEOUT, 6); // Timeout in seconds

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close the cURL session
        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if ($decodedResponse['return']['status'] == 200) {
            return true;
        } else {
            if ($this->debug) {
                throw new SmsException(json_encode($decodedResponse), $decodedResponse['return']['status'], $response);
            }
            return false;
        }
    }
}
