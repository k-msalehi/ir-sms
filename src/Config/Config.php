<?php

namespace KMsalehi\IrSms\Config;

class Config
{
    protected $config;

    public function __construct()
    {
        var_dump(file_exists(__DIR__ . '/../../../../config/ir-sms.php'));
        exit;
        if (file_exists(__DIR__ . '/../../../../config/ir-sms-local.php')) {
            $this->config = include __DIR__ . '/../../../../config/ir-sms-local.php';
        } else {
            $this->config = include __DIR__ . '/../../config/ir-sms.php';
        }
    }

    public function get($key)
    {
        return $this->config[$key] ?? null;
    }

    public function getKey($gateway)
    {
        return $this->config[$gateway]['api_key'] ?? null;
    }

    public function getUsername($gateway)
    {
        return $this->config[$gateway]['username'] ?? null;
    }

    public function getPassword($gateway)
    {
        return $this->config[$gateway]['password'] ?? null;
    }
}
