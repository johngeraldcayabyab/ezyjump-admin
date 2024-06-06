<?php

namespace App\Http\Requests\Auth;

use App\Facades\Authy;
use App\Models\WalletMerchant;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WalletLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $username = $this->input('username');
        $password = $this->input('password');
        $user = WalletMerchant::where('username', $username)->first();
        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }
        $response = $this->walletAuth($user, $username, $password);
        if (!$response['logged_in']) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        if (!Authy::attempt($this->only('username', 'password'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }
        session(['user_metadata' => $response['data']]);
        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')) . '|' . $this->ip());
    }

    public function walletAuth($user, $username, $password)
    {
        $apiKey = $user->merchantKey->api_key;
        $data = [
            'username' => $username,
            'password' => $password,
        ];
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->post('/token', [
                'headers' => [
                    'X-API-KEY' => $apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $responseJson = json_decode($response->getBody(), true);
            $responseJson['logged_in'] = true;
            if (Arr::has($responseJson, 'status') && $responseJson['status'] === 500) {
                $responseJson['logged_in'] = false;
            }
            return $responseJson;
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }
    }
}
