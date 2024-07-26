<?php

namespace KMsalehi\IrSms\Contracts;

interface PatternSend
{
    public function sendByPattern(string $patternId,  array $inputs, string $to, ?string $from);
}
