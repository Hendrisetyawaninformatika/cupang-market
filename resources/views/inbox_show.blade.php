@extends('layouts.app')

@section('title', 'Chat - ' . ($message['from'] ?? 'Pengirim'))

@section('content')
<style>
    .chat-container {
        height: calc(100vh - 280px);
        min-height: 400px;
        overflow-y: auto;
        background: #f8f9fa;
        padding: 20px;
    }
    .message-bubble {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 18px;
        margin-bottom: 12px;
        word-wrap: break-word;
        position: relative;
    }
    .message-sent {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }
    .message-received {
        background: white;
        color: #333;
        margin-right: auto;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .chat-input {
        border-top: 1px solid #e0e0e0;
        background: white;
        padding: 15px;
    }
    .message-time {
        font-size: 11px;
        margin-top: 4px;
        opacity: 0.8;
    }
    .message-sent .message-time {
        color: rgba(255,255,255,0.9);
    }
    .message-received .message-time {
        color: #888;
    }
    .typing-indicator {
        display: none;
        padding: 10px;
        color: #888;
        font-style: italic;
    }
</style>
<meta name="viewport" content="width=1024">

<div class="container py-4">
   {{-- Header Chat --}}
<div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-white rounded shadow-sm">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:45px;height:45px;">
                        <i class="fas fa-user text-info fs-5"></i>
                    </div>
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width:12px;height:12px;"></span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $message['from'] ?? 'Pengirim' }}</h6>
                    <small class="text-muted">
                        <i class="fas fa-phone me-1"></i>{{ $message['sender_phone'] ?? '' }}
                    </small>
                </div>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message['sender_phone'] ?? '') }}" target="_blank">
                        <i class="fab fa-whatsapp text-success me-2"></i>Chat WhatsApp
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">
                        <i class="fas fa-trash me-2"></i>Hapus Chat
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <form id="delete-form" action="{{ route('inbox.destroy', $message['id']) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    {{-- Product Info Card --}}
    @if(!empty($message['product_name']))
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body py-2 px-3 d-flex align-items-center gap-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                <i class="fas fa-fish text-muted fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <small class="text-muted">Produk yang ditanyakan</small>
                <h6 class="mb-0 fw-bold">{{ $message['product_name'] }}</h6>
                @if(!empty($message['product_price']))
                <small class="text-primary fw-bold">Rp {{ number_format($message['product_price'], 0, ',', '.') }}</small>
                @endif
            </div>
            <a href="#" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
        </div>
    </div>
    @endif

   

{{-- Firebase Realtime Chat --}}
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { getDatabase, ref, onValue, push, set, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

    // ============================================
    // KONFIGURASI FIREBASE - GANTI DENGAN ANDA
    // ============================================
    const firebaseConfig = {
  apiKey: "AIzaSyBvvk5T7qsUCKIhFmhjrSIhq2RJUE2BkxY",
  authDomain: "tugas-kuliah-9f509.firebaseapp.com",
  databaseURL: "https://tugas-kuliah-9f509-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "tugas-kuliah-9f509",
  storageBucket: "tugas-kuliah-9f509.firebasestorage.app",
  messagingSenderId: "1092366741123",
  appId: "1:1092366741123:web:c4355dc2ccbc35eb1c35b6",
  measurementId: "G-5FQG5RRYWT"
};
    const app = initializeApp(firebaseConfig);
    const db = getDatabase(app);

    // ============================================
    // DATA DARI LARAVEL
    // ============================================
    const currentUserId = "{{ auth()->id() ?? 'L2GqCoui5aV5bqyxCNHcZt0Gdht1' }}";
    const currentUserName = "{{ auth()->user()->name ?? 'Penjual' }}";
    const senderId = "{{ $message['sender_id'] ?? '' }}";
    const senderName = "{{ $message['from'] ?? 'Pembeli' }}";
    const productId = "{{ $message['product_id'] ?? '' }}";
    const productName = "{{ $message['product_name'] ?? '' }}";

    // Path chat: chats/{receiver_id}/{message_id}/messages
    // Atau gunakan struktur: chats/{room_id}/messages
    const chatRoomId = [currentUserId, senderId].sort().join('_');
    const messagesRef = ref(db, `chat_rooms/${chatRoomId}/messages`);

    const chatContainer = document.getElementById('chat-container');

    // ============================================
    // RENDER PESAN
    // ============================================
    function renderMessage(msg, msgId) {
        const isSent = msg.sender_id === currentUserId;
        const time = formatTime(msg.created_at);

        const div = document.createElement('div');
        div.className = `d-flex mb-3 ${isSent ? 'justify-content-end' : 'justify-content-start'}`;
        div.innerHTML = `
            <div class="message-bubble ${isSent ? 'message-sent' : 'message-received'}">
                <p class="mb-1">${escapeHtml(msg.content || '')}</p>
                <div class="message-time d-flex align-items-center gap-1">
                    <span>${time}</span>
                    ${isSent ? '<i class="fas fa-check-double ms-1"></i>' : ''}
                </div>
            </div>
        `;
        return div;
    }

    // ============================================
    // LISTEN REALTIME - INI YANG PENTING!
    // ============================================
    onValue(messagesRef, (snapshot) => {
        const data = snapshot.val();
        chatContainer.innerHTML = ''; // Clear dulu

        if (!data) {
            // Tampilkan pesan awal dari Laravel
            const initialMsg = {
                sender_id: senderId,
                sender_name: senderName,
                content: "{{ $message['content'] ?? '' }}",
                created_at: {{ $message['created_at'] ?? time() }}
            };
            chatContainer.appendChild(renderMessage(initialMsg, 'initial'));
            return;
        }

        // Render semua pesan
        Object.entries(data).forEach(([msgId, msg]) => {
            chatContainer.appendChild(renderMessage(msg, msgId));
        });

        // Scroll ke bawah
        scrollToBottom();
    });

    // ============================================
    // KIRIM PESAN
    // ============================================
    document.getElementById('send-message-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('message-input');
        const text = input.value.trim();
        if (!text) return;

        // Disable input sementara
        input.disabled = true;
        const btn = e.target.querySelector('button');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const newMessageRef = push(messagesRef);
            await set(newMessageRef, {
                content: text,
                created_at: Math.floor(Date.now() / 1000),
                sender_id: currentUserId,
                sender_name: currentUserName,
                receiver_id: senderId,
                product_id: productId,
                product_name: productName,
                is_read: false
            });

            // Update juga di path inbox untuk notifikasi
            const inboxRef = ref(db, `chats/${currentUserId}/${senderId}`);
            await set(inboxRef, {
                content: text,
                created_at: Math.floor(Date.now() / 1000),
                is_read: true, // Karena ini pesan kita sendiri
                sender_id: currentUserId,
                sender_name: currentUserName,
                receiver_id: senderId,
                product_id: productId,
                product_name: productName
            });

            input.value = '';
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Gagal mengirim pesan. Coba lagi.');
        } finally {
            input.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            input.focus();
        }
    });

    // ============================================
    // UTILS
    // ============================================
    function formatTime(timestamp) {
        if (!timestamp) return '';
        const date = new Date(timestamp * 1000);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);

        if (diff < 60) return 'baru saja';
        if (diff < 3600) return Math.floor(diff / 60) + ' menit';
        if (diff < 86400) return Math.floor(diff / 3600) + ' jam';
        if (diff < 604800) return Math.floor(diff / 86400) + ' hari';

        return date.toLocaleDateString('id-ID', {
            day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Scroll ke bawah saat load
    scrollToBottom();
</script>
@endsection
