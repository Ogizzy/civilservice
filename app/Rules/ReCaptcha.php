<?php

namespace App\Rules;

use Log;
use Closure;
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
        $gresponseToken = (string) $value;
        $response = Http::asForm()
        ->post('https://www.google.com/recaptcha/api/siteverify',
        ['secret' => env('GOOGLE_RECAPTCHA_SECRET_KEY'), 'response' => $gresponseToken]
    );

    \Log::debug(print_r($response->body(), true));
    if (!json_decode($response->body(), true)['success']) {
        $fail('invalid recaptcha');
    }
    }
}
