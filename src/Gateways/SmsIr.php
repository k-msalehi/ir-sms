<?php

namespace KMsalehi\IrSms\Gateways;

use KMsalehi\IrSms\Config\Config;
use KMsalehi\IrSms\Contracts\PatternSend;
use KMsalehi\IrSms\Exceptions\SmsException;

class SmsIr implements PatternSend
{
    protected $apiKey;
    protected $apiUrl = 'https://api.sms.ir/v1/send';
    protected $apiPatternUrl = 'https://api.sms.ir/v1/send/verify';
    protected $from;
    protected $to;

    public function __construct(?string $to = null, ?string $from = null)
    {
        $this->from = $from;
        $this->to = $to;
        $config = new Config();
        $this->apiKey = $config->getKey('sms.ir');
    }

    public function sendByPattern(string $patternId,  array $inputs, string $to, ?string $from = null)
    {
        if (empty($from) && empty($this->from)) {
            throw new SmsException('The sender number is not set');
        }

        $inputs = array_map(function ($row) {
            $key = array_keys($row)[0];
            $value = array_values($row)[0];
            return ['name' => $key, 'value' => $value];
        }, $inputs);

        $messageData = [
            'mobile' => $to,
            'templateId' => $patternId,
            'parameters' => $inputs
        ];
        $messageData = json_encode($messageData, JSON_UNESCAPED_UNICODE);

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $this->apiPatternUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $messageData,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: text/plain',
                    'x-api-key: ' . $this->apiKey
                ),
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
        var_dump($response);
        exit;
        echo $response;
    }
}
