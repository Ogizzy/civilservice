<?php

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\ValidationRule;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
    //     $gresponseToken = (string) $value;
    //     $response = Http::asForm()
    //     ->post('https://www.google.com/recaptcha/api/siteverify',
    //     ['secret' => env('GOOGLE_RECAPTCHA_SECRET_KEY'), 'response' => $gresponseToken]
    // );

    // \Log::debug(print_r($response->body(), true));
    // if (!json_decode($response->body(), true)['success']) {
    //     $fail('invalid recaptcha');
    // }

    $gresponseToken = (string) $value;

    try {
        $response = Http::asForm()
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
                'response' => $gresponseToken,
            ]);

        Log::debug('reCAPTCHA response: ' . $response->body());

        $data = json_decode($response->body(), true);

        if (!isset($data['success']) || !$data['success']) {
            $fail('Invalid reCAPTCHA verification. Please try again.');
        }

    } catch (Exception $e) {
        Log::error('reCAPTCHA validation error: ' . $e->getMessage());
        $fail('Unable to verify reCAPTCHA at this time. Please try again later.');
    }
    
    }
}
