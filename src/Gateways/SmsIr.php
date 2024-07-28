<?php

namespace KMsalehi\IrSms\Gateways;

use KMsalehi\IrSms\Config\Config;
use KMsalehi\IrSms\Contracts\PatternSend;
use KMsalehi\IrSms\Exceptions\SmsException;

// sms.ir gateway
class SmsIr implements PatternSend
{
    protected $apiKey;
    protected $apiUrl = 'https://api.sms.ir/v1/send';
    protected $apiPatternUrl = 'https://api.sms.ir/v1/send/verify';
    protected $from;
    protected $to;
    protected $debug;


    public function __construct(?string $to = null, ?string $from = null)
    {
        $this->from = $from;
        $this->to = $to;
        $config = new Config();
        $this->apiKey = $config->getKey('sms.ir');
        $this->debug = $config->get('debug');
    }

    public function sendByPattern(string $patternId,  array $inputs, string $to, ?string $from = null)
    {
        if (empty($from) && empty($this->from)) {
            throw new SmsException('The sender number is not set. Please set the sender number in the constructor or pass it as an argument.');
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

        $decodedResponse = json_decode($response, true);

        if ($decodedResponse['status'] == 1) {
            return true;
        } else {
            if ($this->debug) {
                throw new SmsException($decodedResponse['message'], $decodedResponse['status'], $response);
            }
            return false;
        }
    }
}
