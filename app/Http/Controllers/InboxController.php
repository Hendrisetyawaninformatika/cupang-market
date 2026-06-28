<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InboxController extends Controller
{
    /**
     * URL Firebase Realtime Database Anda
     * Ganti dengan URL project Firebase Anda
     */
    protected $firebaseUrl;
    protected $receiverId;

    public function __construct()
    {
        // Ganti dengan URL Firebase Anda yang sebenarnya
        $this->firebaseUrl = 'https://your-project.firebaseio.com';
        // ID user yang sedang login (receiver)
        $this->receiverId = auth()->id() ?? 'L2GqCoui5aV5bqyxCNHcZt0Gdht1';
    }

    public function index(Request $request)
    {
        try {
            // Ambil semua pesan dari Firebase: chats/{receiver_id}
            $response = Http::get("{$this->firebaseUrl}/chats/{$this->receiverId}.json");

            if (!$response->successful()) {
                throw new \Exception('Gagal mengambil data dari Firebase');
            }

            $firebaseMessages = $response->json();

            // Jika tidak ada data, tampilkan kosong
            if (empty($firebaseMessages)) {
                $messages = [];
                $unreadCount = 0;
                return view('inbox', compact('messages', 'unreadCount'));
            }

            // Format data Firebase ke struktur yang dibutuhkan template
            $messages = [];
            $unreadCount = 0;

            foreach ($firebaseMessages as $id => $msg) {
                // Hitung unread
                if (!($msg['is_read'] ?? false)) {
                    $unreadCount++;
                }

                $messages[$id] = [
                    'sender_name' => $msg['sender_name'] ?? 'Pengirim',
                    'content' => $msg['content'] ?? '',
                    'created_at' => $msg['created_at'] ?? time(),
                    'is_read' => $msg['is_read'] ?? false,
                    'product_name' => $msg['product_name'] ?? null,
                    'sender_phone' => $msg['sender_phone'] ?? '',
                    'sender_id' => $msg['sender_id'] ?? '',
                    'product_id' => $msg['product_id'] ?? '',
                ];
            }

            // Urutkan berdasarkan waktu terbaru (descending)
            uasort($messages, function ($a, $b) {
                return ($b['created_at'] ?? 0) <=> ($a['created_at'] ?? 0);
            });

            return view('inbox', compact('messages', 'unreadCount'));

        } catch (\Exception $e) {
            Log::error('Firebase Error: ' . $e->getMessage());
            
            // Tampilkan error atau kosong
            $messages = [];
            $unreadCount = 0;
            return view('inbox', compact('messages', 'unreadCount'))
                ->with('error', 'Gagal memuat pesan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // Ambil pesan spesifik
            $response = Http::get("{$this->firebaseUrl}/chats/{$this->receiverId}/{$id}.json");

            if (!$response->successful() || empty($response->json())) {
                return redirect()->route('inbox')->with('error', 'Pesan tidak ditemukan');
            }

            $msg = $response->json();

            // Tandai sudah dibaca di Firebase
            Http::patch("{$this->firebaseUrl}/chats/{$this->receiverId}/{$id}.json", [
                'is_read' => true
            ]);

            // Format untuk view show
            $message = [
                'id' => $id,
                'from' => $msg['sender_name'] ?? 'Pengirim',
                'sender_photo' => null,
                'sender_phone' => $msg['sender_phone'] ?? '',
                'sender_location' => 'Indonesia',
                'sender_joined' => 'Baru',
                'time' => $this->formatTime($msg['created_at'] ?? time()),
                'read' => true,
                'content' => $msg['content'] ?? '',
                'product_name' => $msg['product_name'] ?? '',
                'product_image' => null,
                'product_price' => 0,
            ];

            return view('inbox_show', compact('message'));

        } catch (\Exception $e) {
            Log::error('Firebase Show Error: ' . $e->getMessage());
            return redirect()->route('inbox')->with('error', 'Gagal memuat pesan');
        }
    }

    public function destroy($id)
    {
        try {
            Http::delete("{$this->firebaseUrl}/chats/{$this->receiverId}/{$id}.json");
            return redirect()->route('inbox')->with('success', 'Pesan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('inbox')->with('error', 'Gagal menghapus pesan');
        }
    }

    /**
     * Format timestamp ke string waktu relatif
     */
    private function formatTime($timestamp)
    {
        if (is_string($timestamp)) {
            $timestamp = (int) $timestamp;
        }

        $diff = time() - $timestamp;

        if ($diff < 60) return 'baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
        if ($diff < 604800) return floor($diff / 86400) . ' hari yang lalu';

        return date('d M Y', $timestamp);
    }
}