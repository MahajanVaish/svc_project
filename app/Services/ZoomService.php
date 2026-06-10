<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    protected $baseUrl = 'https://api.zoom.us/v2';

    public function getToken()
    {
        // dd(strlen(config('services.zoom.client_secret')));

        try {
            $clientId = trim(config('services.zoom.client_id'));
            $clientSecret = trim(config('services.zoom.client_secret'));
            $accountId = trim(config('services.zoom.account_id'));

            if (!$clientId || !$clientSecret || !$accountId) {
                $missing = [];
                if (!$clientId) $missing[] = 'Client ID';
                if (!$clientSecret) $missing[] = 'Client Secret';
                if (!$accountId) $missing[] = 'Account ID';
                throw new \Exception('Zoom credentials missing in config: ' . implode(', ', $missing));
            }

          
            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post("https://zoom.us/oauth/token", [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId
                ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

         
            $response = Http::withBasicAuth($clientId, $clientSecret)
                ->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$accountId}");

            if ($response->successful()) {
                return $response->json('access_token');
            }

            $errorBody = $response->body();
            Log::error('Zoom Token Generation Failed', [
                'status' => $response->status(),
                'response' => $errorBody,
                'clientId' => substr($clientId, 0, 5) . '...',
                'accountId' => $accountId
            ]);

            throw new \Exception('Zoom API Error: ' . $errorBody);
        } catch (\Exception $e) {
            Log::error('Zoom Token Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createMeeting($topic, $startTime, $duration = 30)
    {
        try {
            $token = $this->getToken();

            if (!$token) {
                return ['error' => 'Could not generate Zoom access token. Check your logs for details.'];
            }

            $response = Http::withToken($token)->post("{$this->baseUrl}/users/me/meetings", [
                'topic' => $topic,
                'type' => 2, // Scheduled meeting
                'start_time' => $startTime,
                'duration' => $duration,
                'timezone' => 'UTC',
                'settings' => [
                    'join_before_host' => true,
                    'host_video' => true,
                    'participant_video' => true,
                    'mute_upon_entry' => true,
                    'waiting_room' => false,
                ]
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            $errorBody = $response->body();
            Log::error('Zoom Meeting Creation Failed', [
                'status' => $response->status(),
                'response' => $errorBody
            ]);

            return ['error' => 'Zoom API error: ' . $errorBody];
        } catch (\Exception $e) {
            Log::error('Zoom Meeting Exception: ' . $e->getMessage());
            return ['error' => 'Error: ' . $e->getMessage()];
        }
    }
}
