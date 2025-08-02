<?php
// app/Services/VonageService.php

namespace App\Services;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class VonageService
{
    protected $client;

    public function __construct()
    {
        $credentials = new Basic(
            config('services.vonage.key'),
            config('services.vonage.secret')
        );

        $this->client = new Client($credentials);
    }

    public function sendSms($to, $message)
    {
        $sms = new SMS($to, config('services.vonage.from'), $message);
        return $this->client->sms()->send($sms);
    }
}
