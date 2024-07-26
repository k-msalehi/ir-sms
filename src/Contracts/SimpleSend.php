<?php

namespace KMsalehi\IrSms\Contracts;

interface SimpleSend
{
    public function send(string $to, string $message): bool;
}
