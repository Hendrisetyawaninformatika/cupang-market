<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $databaseUrl;

    public function __construct()
    {
        $this->databaseUrl = rtrim(env('FIREBASE_DATABASE_URL', ''), '/');
    }

    public function edit($id)
    {
        $user = Session::get('firebase_user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        try {
            // Ambil data user dari Firebase - pakai UID dari session
            $uid = $user['uid'];
            $response = Http::get($this->databaseUrl . '/users/' . $uid . '.json');
            $userData = $response->json() ?? [];

            $seller = [
                'id' => $uid,
                'name' => $userData['name'] ?? $user['displayName'] ?? 'Penjual',
                'photo' => $userData['photo'] ?? null,
                'whatsapp' => $userData['whatsapp'] ?? '',
                'email' => $user['email'] ?? '',
                'bio' => $userData['bio'] ?? '',
            ];

            return view('profile_edit', compact('seller'));
        } catch (\Exception $e) {
            Log::error('Gagal load profil: ' . $e->getMessage());
            $seller = [
                'id' => $user['uid'],
                'name' => $user['displayName'] ?? 'Penjual',
                'photo' => null,
                'whatsapp' => '',
                'email' => $user['email'] ?? '',
                'bio' => '',
            ];
            return view('profile_edit', compact('seller'));
        }
    }

    public function update(Request $request, $id)
    {
        $user = Session::get('firebase_user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bio' => 'nullable|string|max:500',
        ]);

        try {
            $uid = $user['uid'];

            $updateData = [
                'name' => $request->name,
                'whatsapp' => $request->whatsapp,
                'bio' => $request->bio,
                'email' => $user['email'],
                'updatedAt' => time(),
            ];

            // Handle foto upload
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $file = $request->file('photo');
                $imageData = file_get_contents($file->getRealPath());
                $mimeType = $file->getMimeType();
                $updateData['photo'] = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
            }

            // Simpan ke Firebase - pakai PUT untuk replace data
            Http::put($this->databaseUrl . '/users/' . $uid . '.json', $updateData);

            // Update session
            $user['displayName'] = $request->name;
            Session::put('firebase_user', $user);

            return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal update profil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }
}