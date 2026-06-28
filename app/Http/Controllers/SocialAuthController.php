<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $params = [
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'redirect_uri'  => url('/auth/google/callback'),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'online',
            'prompt'        => 'select_account',
        ];
        
        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        return redirect($url);
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect('/login')->with('error', 'Login Google dibatalkan');
        }

        try {
            // Tukar code dengan access token
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code'          => $code,
                'client_id'     => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri'  => url('/auth/google/callback'),
                'grant_type'    => 'authorization_code',
            ]);

            $tokenData = $tokenResponse->json();

            if (isset($tokenData['error'])) {
                return redirect('/login')->with('error', 'Gagal autentikasi Google: ' . $tokenData['error']);
            }

            // Ambil data user dari Google
            $userResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenData['access_token']
            ])->get('https://www.googleapis.com/oauth2/v2/userinfo');

            $googleUser = $userResponse->json();

            if (!isset($googleUser['email'])) {
                return redirect('/login')->with('error', 'Email tidak ditemukan dari Google');
            }

            // Cari atau buat user
            $user = User::updateOrCreate(
                ['email' => $googleUser['email']],
                [
                    'name'       => $googleUser['name'] ?? 'User',
                    'google_id'  => $googleUser['id'],
                    'avatar'     => $googleUser['picture'] ?? null,
                ]
            );

            // Login user
            Auth::login($user);

            return redirect('/dashboard')->with('success', 'Berhasil login dengan Google!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}