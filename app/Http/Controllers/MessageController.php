<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    protected $databaseUrl;

    public function __construct()
    {
        $this->databaseUrl = rtrim(env('FIREBASE_DATABASE_URL', ''), '/');
    }

    // ========== KIRIM PESAN ==========
    public function store(Request $request)
    {
        try {
            $request->validate([
                'receiver_id' => 'required|string',
                'product_id' => 'nullable|string',
                'product_name' => 'nullable|string',
                'sender_name' => 'required|string|max:255',
                'sender_phone' => 'required|string|max:20',
                'content' => 'required|string|max:1000',
            ]);

            $senderId = Session::get('firebase_user.uid') ?? 'guest_' . uniqid();

            $messageData = [
                'sender_id' => $senderId,
                'sender_name' => $request->sender_name,
                'sender_phone' => $request->sender_phone,
                'receiver_id' => $request->receiver_id,
                'product_id' => $request->product_id,
                'product_name' => $request->product_name,
                'content' => $request->content,
                'is_read' => false,
                'created_at' => time(),
            ];

            $response = Http::post($this->databaseUrl . '/messages.json', $messageData);
            $result = $response->json();

            if (isset($result['name'])) {
                Log::info('Pesan berhasil dikirim', ['id' => $result['name']]);
                return redirect()->back()->with('success', 'Pesan berhasil dikirim! Penjual akan menghubungi kamu segera.');
            } else {
                Log::error('Gagal kirim pesan - tidak ada ID', ['response' => $result]);
                return redirect()->back()->with('error', 'Gagal mengirim pesan. Coba lagi.')->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal kirim pesan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim pesan: ' . $e->getMessage())->withInput();
        }
    }

    // ========== KOTAK MASUK ==========
    public function inbox()
    {
        try {
            $userId = Session::get('firebase_user.uid');

            if (!$userId) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            $response = Http::get($this->databaseUrl . '/messages.json');
            $allMessages = $response->json() ?? [];

            $messages = [];
            foreach ($allMessages as $id => $msg) {
                if (!is_array($msg)) continue;
                if (($msg['receiver_id'] ?? '') === $userId) {
                    $msg['id'] = $id;
                    $messages[$id] = $msg;
                }
            }

            // Urutkan berdasarkan created_at terbaru
            uasort($messages, function($a, $b) {
                return ($b['created_at'] ?? 0) <=> ($a['created_at'] ?? 0);
            });

            $unreadCount = count(array_filter($messages, function($m) {
                return !($m['is_read'] ?? false);
            }));

            return view('inbox', compact('messages', 'unreadCount'));

        } catch (\Exception $e) {
            Log::error('Gagal load inbox: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat kotak masuk');
        }
    }

    // ========== DETAIL PESAN ==========
    public function show($id)
    {
        try {
            $userId = Session::get('firebase_user.uid');

            if (!$userId) {
                return redirect()->route('login');
            }

            $response = Http::get($this->databaseUrl . '/messages/' . $id . '.json');
            $message = $response->json();

            if (!$message || ($message['receiver_id'] ?? '') !== $userId) {
                return redirect()->route('inbox')->with('error', 'Pesan tidak ditemukan');
            }

            $message['id'] = $id;

            // Tandai sebagai dibaca
            if (!($message['is_read'] ?? false)) {
                Http::patch($this->databaseUrl . '/messages/' . $id . '.json', ['is_read' => true]);
                $message['is_read'] = true;
            }

            return view('inbox_show', compact('message'));

        } catch (\Exception $e) {
            Log::error('Gagal load pesan: ' . $e->getMessage());
            return redirect()->route('inbox')->with('error', 'Gagal memuat pesan');
        }
    }

    // ========== HAPUS PESAN ==========
    public function destroy($id)
    {
        try {
            $userId = Session::get('firebase_user.uid');

            $response = Http::get($this->databaseUrl . '/messages/' . $id . '.json');
            $message = $response->json();

            if ($message && ($message['receiver_id'] ?? '') === $userId) {
                Http::delete($this->databaseUrl . '/messages/' . $id . '.json');
            }

            return redirect()->route('inbox')->with('success', 'Pesan dihapus');

        } catch (\Exception $e) {
            Log::error('Gagal hapus pesan: ' . $e->getMessage());
            return redirect()->route('inbox')->with('error', 'Gagal menghapus pesan');
        }
    }

    // ========== API RECENT ==========
    public function recent(Request $request)
    {
        try {
            $userId = Session::get('firebase_user.uid');

            if (!$userId) {
                return response()->json([]);
            }

            $response = Http::get($this->databaseUrl . '/messages.json');
            $allMessages = $response->json() ?? [];

            $messages = [];
            foreach ($allMessages as $id => $msg) {
                if (!is_array($msg)) continue;
                if (($msg['receiver_id'] ?? '') === $userId) {
                    $msg['id'] = $id;
                    $messages[$id] = $msg;
                }
            }

            uasort($messages, function($a, $b) {
                return ($b['created_at'] ?? 0) <=> ($a['created_at'] ?? 0);
            });

            $messages = array_slice($messages, 0, 5, true);

            $result = [];
            foreach ($messages as $id => $msg) {
                $diff = time() - ($msg['created_at'] ?? 0);
                if ($diff < 60) $timeStr = 'baru saja';
                elseif ($diff < 3600) $timeStr = floor($diff / 60) . ' menit';
                elseif ($diff < 86400) $timeStr = floor($diff / 3600) . ' jam';
                else $timeStr = floor($diff / 86400) . ' hari';

                $result[] = [
                    'id' => $id,
                    'from' => $msg['sender_name'] ?? 'Pengirim',
                    'preview' => substr($msg['content'] ?? '', 0, 50),
                    'time' => $timeStr,
                    'read' => $msg['is_read'] ?? false,
                    'product_name' => $msg['product_name'] ?? null,
                ];
            }

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Gagal load recent: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}