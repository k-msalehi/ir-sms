<?php

interface Sms
{
    public function send(string $to, string $message): bool;
}