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

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
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

            $this->firebase->saveUser($data['localId'], [
                'name' => $request->name,
                'email' => $request->email,
                'createdAt' => now()->timestamp,
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
    }

    public function dashboard()
    {
        $user = Session::get('firebase_user');
        if (!$user) {
            return redirect()->route('login');
        }

        $products = $this->firebase->getAllProducts();
        $myProducts = array_filter($products, function($p) use ($user) {
            return ($p['sellerId'] ?? '') === $user['uid'];
        });

        $stats = [
            'totalProducts' => count($products),
            'myProducts' => count($myProducts),
            'avgPrice' => count($products) > 0
                ? round(array_sum(array_column($products, 'price')) / count($products))
                : 0,
        ];

        return view('dashboard', compact('myProducts', 'stats'));
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

        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|in:Halfmoon,Plakat,Crowntail,Double Tail,Super Delta,Lainnya',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $imageBase64 = null;
            
            // Proses gambar jika ada
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                
                // Cek ukuran file
                if ($file->getSize() > 2 * 1024 * 1024) {
                    return back()->with('error', 'Gambar terlalu besar! Maksimal 2MB')->withInput();
                }
                
                // Convert ke Base64
                $imageData = file_get_contents($file->getRealPath());
                $mimeType = $file->getMimeType();
                $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                
                Log::info('Gambar berhasil di-convert ke Base64, ukuran: ' . strlen($imageBase64));
            }

            // Siapkan data untuk Firebase
            $productData = [
                'name' => $validated['name'],
                'category' => $validated['category'],
                'price' => (int) $validated['price'],
                'description' => $validated['description'],
                'imageBase64' => $imageBase64,
                'sellerId' => $user['uid'],
                'sellerName' => $user['displayName'] ?? $user['email'],
                'createdAt' => now()->timestamp,
                'updatedAt' => now()->timestamp,
            ];

            Log::info('Menyimpan produk ke Firebase', ['name' => $validated['name']]);

            // Simpan ke Firebase
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
                'updatedAt' => now()->timestamp,
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