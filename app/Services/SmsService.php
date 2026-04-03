<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $smsApiKey;

    public function __construct()
    {
        $this->smsApiKey = config('services.fast2sms.api_key');
    }

    public function sendOtp($phone, $otp)
    {
        return Http::withHeaders([
            'authorization' => $this->smsApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://www.fast2sms.com/dev/bulkV2', [
            'route' => 'dlt',
            'sender_id' => 'JKCHNR',
            'message' => '165676',
            'variables_values' => $otp,
            'flash' => 0,
            'numbers' => $phone,
        ]);
    }
}