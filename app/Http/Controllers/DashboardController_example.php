<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = session('firebase_user.uid');

        // Data seller (ganti dengan data dari database/Firebase)
        $seller = [
            'id' => $userId,
            'name' => session('firebase_user.displayName') ?? 'Penjual',
            'photo' => session('firebase_user.photoURL') ?? null,
            'whatsapp' => '08123456789', // Ambil dari database
        ];

        // Statistik
        $stats = [
            'totalProducts' => 0,  // Hitung dari database
            'myProducts' => 0,
            'avgPrice' => 0,
            'soldProducts' => 0,
            'rating' => '0.0',
        ];

        // Pesan belum dibaca
        $unreadMessages = 0;
        $recentMessages = [];

        if ($userId) {
            $unreadMessages = Message::where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $recentMessages = Message::where('receiver_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($msg) {
                    return [
                        'id' => $msg->id,
                        'from' => $msg->sender_name,
                        'preview' => substr($msg->content, 0, 50),
                        'time' => $msg->created_at->diffForHumans(),
                        'read' => $msg->is_read,
                    ];
                })->toArray();
        }

        $myProducts = []; // Ambil dari database

        return view('dashboard', compact(
            'seller', 'stats', 'myProducts', 'unreadMessages', 'recentMessages'
        ));
    }
}