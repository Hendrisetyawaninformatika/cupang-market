<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CupangController extends Controller
{
    protected FirebaseService $firebase;
    protected $databaseUrl;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
        $this->databaseUrl = rtrim(env('FIREBASE_DATABASE_URL', ''), '/');
    }

    // ========== AUTH ==========

    public function showLogin()
    {
        if (Session::has('firebase_user')) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $response = Http::post('https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=' . env('FIREBASE_API_KEY'), [
                'email' => $request->email,
                'password' => $request->password,
                'returnSecureToken' => true,
            ]);

            if ($response->failed()) {
                $error = $response->json('error.message');
                return back()->with('error', $this->getAuthError($error));
            }

            $data = $response->json();
            $userData = $this->firebase->getUser($data['localId']) ?? [];

            Session::put('firebase_user', [
                'uid' => $data['localId'],
                'email' => $data['email'],
                'idToken' => $data['idToken'],
                'displayName' => $userData['name'] ?? explode('@', $data['email'])[0],
            ]);

            return redirect()->route('home')->with('success', 'Berhasil masuk!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal masuk: ' . $e->getMessage());
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        try {
            $response = Http::post('https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=' . env('FIREBASE_API_KEY'), [
                'email' => $request->email,
                'password' => $request->password,
                'returnSecureToken' => true,
            ]);

            if ($response->failed()) {
                $error = $response->json('error.message');
                return back()->with('error', $this->getAuthError($error));
            }

            $data = $response->json();

            // Simpan user ke /users/{uid}
            Http::put($this->databaseUrl . '/users/' . $data['localId'] . '.json', [
                'name' => $request->name,
                'email' => $request->email,
                'whatsapp' => '',
                'photo' => null,
                'bio' => '',
                'createdAt' => time(),
            ]);

            Http::post('https://identitytoolkit.googleapis.com/v1/accounts:update?key=' . env('FIREBASE_API_KEY'), [
                'idToken' => $data['idToken'],
                'displayName' => $request->name,
                'returnSecureToken' => true,
            ]);

            Session::put('firebase_user', [
                'uid' => $data['localId'],
                'email' => $data['email'],
                'idToken' => $data['idToken'],
                'displayName' => $request->name,
            ]);

            return redirect()->route('home')->with('success', 'Akun berhasil dibuat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal daftar: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Session::forget('firebase_user');
        return redirect()->route('login')->with('success', 'Berhasil keluar');
    }

    private function getAuthError($error)
    {
        $map = [
            'EMAIL_EXISTS' => 'Email sudah terdaftar',
            'INVALID_EMAIL' => 'Format email tidak valid',
            'INVALID_PASSWORD' => 'Password salah',
            'INVALID_LOGIN_CREDENTIALS' => 'Email atau password salah',
            'WEAK_PASSWORD' => 'Password terlalu lemah',
        ];
        return $map[$error] ?? 'Terjadi kesalahan';
    }

    // ========== PAGES ==========

    public function home(Request $request)
    {
        try {
            $products = $this->firebase->getAllProducts();

            if ($request->has('search')) {
                $search = strtolower($request->search);
                $products = array_filter($products, function($p) use ($search) {
                    return str_contains(strtolower($p['name'] ?? ''), $search) ||
                           str_contains(strtolower($p['description'] ?? ''), $search);
                });
            }

            if ($request->has('category') && $request->category) {
                $products = array_filter($products, function($p) use ($request) {
                    return ($p['category'] ?? '') === $request->category;
                });
            }

            uasort($products, function($a, $b) {
                return ($b['createdAt'] ?? 0) <=> ($a['createdAt'] ?? 0);
            });

            return view('home', compact('products'));
        } catch (\Exception $e) {
            Log::error('Gagal load home: ' . $e->getMessage());
            return view('home', ['products' => []])->with('error', 'Gagal memuat produk');
        }
    }

    public function dashboard()
    {
        try {
            $user = Session::get('firebase_user');
            if (!$user) {
                return redirect()->route('login');
            }

            $uid = $user['uid'];

            // Ambil produk
            $products = $this->firebase->getAllProducts();
            $myProducts = array_filter($products, function($p) use ($uid) {
                return ($p['sellerId'] ?? '') === $uid;
            });

            $stats = [
                'totalProducts' => count($products),
                'myProducts' => count($myProducts),
                'avgPrice' => count($products) > 0
                    ? round(array_sum(array_column($products, 'price')) / count($products))
                    : 0,
                'soldProducts' => 0,
                'rating' => '0.0',
            ];

            // Ambil data seller dari Firebase
            $seller = [
                'id' => $uid,
                'name' => $user['displayName'] ?? $user['email'],
                'photo' => null,
                'whatsapp' => 'Belum diatur',
                'email' => $user['email'],
            ];

            try {
                $userResponse = Http::get($this->databaseUrl . '/users/' . $uid . '.json');
                $userData = $userResponse->json() ?? [];

                if ($userData) {
                    $seller['name'] = $userData['name'] ?? $seller['name'];
                    $seller['photo'] = $userData['photo'] ?? null;
                    $seller['whatsapp'] = $userData['whatsapp'] ?? 'Belum diatur';
                    $seller['email'] = $userData['email'] ?? $seller['email'];
                }
            } catch (\Exception $e) {
                Log::warning('Gagal ambil data user: ' . $e->getMessage());
            }

            // Ambil pesan masuk dari Firebase
            $unreadMessages = 0;
            $recentMessages = [];

            try {
                $msgResponse = Http::get($this->databaseUrl . '/messages.json');
                $allMessages = $msgResponse->json() ?? [];

                if (is_array($allMessages) && count($allMessages) > 0) {
                    // Filter pesan untuk user ini
                    $myMessages = [];
                    foreach ($allMessages as $msgId => $msg) {
                        if (!is_array($msg)) continue;
                        if (($msg['receiver_id'] ?? '') === $uid) {
                            $msg['id'] = $msgId;
                            $myMessages[$msgId] = $msg;
                        }
                    }

                    // Urutkan pesan terbaru
                    uasort($myMessages, function($a, $b) {
                        $aTime = $a['created_at'] ?? 0;
                        $bTime = $b['created_at'] ?? 0;
                        return $bTime <=> $aTime;
                    });

                    // Hitung unread
                    $unreadMessages = count(array_filter($myMessages, function($m) {
                        return !($m['is_read'] ?? false);
                    }));

                    // Ambil 5 pesan terbaru
                    $recent = array_slice($myMessages, 0, 5, true);

                    foreach ($recent as $msgId => $msg) {
                        $diff = time() - ($msg['created_at'] ?? 0);
                        if ($diff < 60) $timeStr = 'baru saja';
                        elseif ($diff < 3600) $timeStr = floor($diff / 60) . ' menit';
                        elseif ($diff < 86400) $timeStr = floor($diff / 3600) . ' jam';
                        else $timeStr = floor($diff / 86400) . ' hari';

                        $recentMessages[] = [
                            'id' => $msgId,
                            'from' => $msg['sender_name'] ?? 'Pengirim',
                            'preview' => substr($msg['content'] ?? '', 0, 50),
                            'time' => $timeStr,
                            'read' => $msg['is_read'] ?? false,
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Gagal ambil pesan: ' . $e->getMessage());
            }

            return view('dashboard', compact('myProducts', 'stats', 'seller', 'unreadMessages', 'recentMessages'));

        } catch (\Exception $e) {
            Log::error('Gagal load dashboard: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Gagal memuat dashboard');
        }
    }

    // ========== CRUD ==========

    public function create()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        return view('products.create');
    }

    public function store(Request $request)
    {
        $user = Session::get('firebase_user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|in:Halfmoon,Plakat,Crowntail,Double Tail,Super Delta,Lainnya',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $imageBase64 = null;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');

                if ($file->getSize() > 2 * 1024 * 1024) {
                    return back()->with('error', 'Gambar terlalu besar! Maksimal 2MB')->withInput();
                }

                $imageData = file_get_contents($file->getRealPath());
                $mimeType = $file->getMimeType();
                $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);

                Log::info('Gambar berhasil di-convert ke Base64, ukuran: ' . strlen($imageBase64));
            }

            // Ambil nomor WA dari profil user
            $sellerPhone = '';
            try {
                $userResp = Http::get($this->databaseUrl . '/users/' . $user['uid'] . '.json');
                $userData = $userResp->json() ?? [];
                $sellerPhone = $userData['whatsapp'] ?? '';
            } catch (\Exception $e) {
                Log::warning('Gagal ambil no WA: ' . $e->getMessage());
            }

            $productData = [
                'name' => $validated['name'],
                'category' => $validated['category'],
                'price' => (int) $validated['price'],
                'description' => $validated['description'],
                'imageBase64' => $imageBase64,
                'sellerId' => $user['uid'],
                'sellerName' => $user['displayName'] ?? $user['email'],
                'sellerPhone' => $sellerPhone,
                'sellerWhatsapp' => $sellerPhone,
                'createdAt' => time(),
                'updatedAt' => time(),
            ];

            Log::info('Menyimpan produk ke Firebase', ['name' => $validated['name']]);

            $productId = $this->firebase->createProduct($productData);

            Log::info('Produk berhasil disimpan', ['id' => $productId]);

            return redirect()->route('home')->with('success', 'Ikan "' . $validated['name'] . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan produk: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $user = Session::get('firebase_user');
        if (!$user) {
            return redirect()->route('login');
        }

        $product = $this->firebase->getProduct($id);
        if (!$product) {
            return redirect()->route('home')->with('error', 'Produk tidak ditemukan');
        }

        if ($product['sellerId'] !== $user['uid']) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses');
        }

        return view('products.edit', compact('product', 'id'));
    }

    public function update(Request $request, $id)
    {
        $user = Session::get('firebase_user');
        if (!$user) {
            return redirect()->route('login');
        }

        $product = $this->firebase->getProduct($id);
        if (!$product || $product['sellerId'] !== $user['uid']) {
            return redirect()->route('home')->with('error', 'Akses ditolak');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|in:Halfmoon,Plakat,Crowntail,Double Tail,Super Delta,Lainnya',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $updateData = [
                'name' => $validated['name'],
                'category' => $validated['category'],
                'price' => (int) $validated['price'],
                'description' => $validated['description'],
                'updatedAt' => time(),
            ];

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $imageData = file_get_contents($file->getRealPath());
                $mimeType = $file->getMimeType();
                $updateData['imageBase64'] = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
            }

            $this->firebase->updateProduct($id, $updateData);

            return redirect()->route('dashboard')->with('success', 'Ikan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Gagal update produk: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $user = Session::get('firebase_user');
        if (!$user) {
            return redirect()->route('login');
        }

        $product = $this->firebase->getProduct($id);
        if (!$product || $product['sellerId'] !== $user['uid']) {
            return redirect()->route('home')->with('error', 'Akses ditolak');
        }

        try {
            $this->firebase->deleteProduct($id);
            return redirect()->route('dashboard')->with('success', 'Ikan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal hapus produk: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}