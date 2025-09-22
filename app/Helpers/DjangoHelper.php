<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DjangoHelper
{
    // Static method to get the Django token
    public static function getDjangoToken()
    {
        // Check if 'django_token' exists in the session
        if (!session()->has('django_token')) {
            // Make the POST request to Django API for login
            $response = Http::post('http://127.0.0.1:8000/api/login/', [
                'username' => 'munim',
                'password' => '12345678',
            ]);

            // If the request was successful, store the token in the session
            if ($response->successful()) {
                session(['django_token' => $response->json()['access']]);
            } else {
                // Throw an exception if the request fails
                throw new \Exception('Failed to get Django token');
            }
        }

        // Return the token from the session
        return session('django_token');
    }
}
