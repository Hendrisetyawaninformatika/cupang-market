BettaCupangMarket

Sistem Marketplace Jual Beli Ikan Cupang Berbasis Website Menggunakan Framework Laravel

Identitas Project

Judul:
Sistem Marketplace Jual Beli Ikan Cupang Berbasis Website Menggunakan Framework Laravel

Nama Kelompok:Naah Wibu

Website Demo:
https://violet-mole-563384.hostingersite.com/

Anggota Kelompok
1.	Augitian Alba Setiaji — NIM: 241120110 (Ketua)
2.	Hendri Setyawan (NIM: 241110041) 
3.	Danisa Arif Nugroho (NIM: 241110032)
4.	Nadi Supriadi (NIM: 241110054)

Product Requirement Document (PRD) — BettaMarket: Marketplace Jual Beli Ikan Cupang
1. Ringkasan Eksekutif & Tujuan
Tujuan dari project ini adalah membangun sebuah platform web jual beli (marketplace) yang menghubungkan penjual (breeder/peternak ikan cupang) dengan pembeli secara langsung melalui sistem etalase produk, keranjang belanja, dan transaksi pemesanan. Aplikasi ini terinspirasi dari konsep marketplace umum (seperti Tokopedia/Shopee) dengan penyederhanaan fitur khusus untuk kebutuhan jual beli ikan cupang hias, termasuk pengelompokan berdasarkan jenis ekor (Halfmoon, Plakat, Crowntail, Double Tail, Super Delta, dan lainnya).
2. Teknologi yang Digunakan (Tech Stack)
3. 
Aplikasi ini dikembangkan menggunakan kombinasi teknologi modern untuk menghasilkan aplikasi web yang interaktif, responsif, dan aman:
•Backend Framework: Laravel (PHP) — Menyediakan arsitektur MVC, ORM (Eloquent), routing, keamanan, dan manajemen basis data.
•Autentikasi: Laravel Breeze/Sanctum atau autentikasi bawaan Laravel — untuk membedakan peran Penjual dan Pembeli (role-based access).
•Database: MySQL — untuk penyimpanan relasional data pengguna, produk (ikan cupang), kategori, keranjang, pesanan, dan transaksi.
•CSS Styling: Tailwind CSS / Bootstrap — dengan desain antarmuka yang modern, responsif (mobile-friendly), dan ramah pengguna untuk pasar e-commerce.
•Alert & Notifikasi: SweetAlert2 — untuk dialog konfirmasi (misalnya saat menghapus produk atau membatalkan pesanan) serta notifikasi sukses/gagal transaksi.
•Penyimpanan Gambar: Local Storage Laravel (storage/app/public) — untuk menyimpan foto produk ikan cupang yang diunggah penjual.
4. Fitur Utama & Kebutuhan Fungsional
A. Autentikasi & Manajemen Pengguna
•Registrasi & Login: Pengguna dapat mendaftar sebagai Pembeli atau Penjual, serta login ke sistem dengan email dan kata sandi yang di-hash menggunakan bcrypt (standar Laravel).
•Manajemen Profil: Pengguna dapat mengedit informasi profil seperti nama, nomor telepon, alamat pengiriman, dan foto profil.
•Verifikasi Penjual: Penjual dapat melengkapi profil toko (nama toko, deskripsi, lokasi) sebelum dapat memasarkan produk.
B. Manajemen Etalase / Produk (Ikan Cupang)
•Tambah Produk: Penjual dapat menambahkan listing ikan cupang baru dengan menyertakan nama/kode ikan, foto, harga, stok, jenis ekor (kategori), umur, dan deskripsi (corak, asal indukan, dsb).
•Kategori Produk: Pengelompokan berdasarkan jenis ekor — Halfmoon (sirip lebar), Plakat (sirip pendek), Crowntail (sirip berduri), Double Tail (dua ekor), Super Delta (sirip delta), dan Lainnya (jenis lain).
•Edit & Hapus Produk: Penjual dapat mengubah informasi atau menghapus listing produk miliknya dengan konfirmasi SweetAlert.
•Detail Produk: Halaman detail untuk melihat foto ikan secara penuh, harga, stok tersedia, info penjual, deskripsi lengkap, dan tombol tambah ke keranjang/beli langsung.
C. Transaksi & Keranjang Belanja
•	Keranjang Belanja: Pembeli dapat menabahkan satu atau beberapa ikan cupang ke keranjang sebelum checkout.
•	Checkout & Pemesanan: Pembeli dapat melakukan checkout dengan mengisi alamat pengiriman dan memilih metode pembayaran/pengiriman.
•	Riwayat Pesanan: Pembeli dan penjual dapat melihat daftar riwayat transaksi beserta statusnya (menunggu konfirmasi, dikirim, selesai, dibatalkan).
•	Konfirmasi Pesanan: Penjual dapat mengonfirmasi atau menolak pesanan masuk, serta memperbarui status pengiriman.
D. Interaksi & Sosial
•	Ulasan & Rating: Pembeli dapat memberikan rating dan ulasan terhadap produk/penjual setelah transaksi selesai.
•	Wishlist (Favorit): Pembeli dapat menyimpan produk ikan cupang favorit untuk dibeli di kemudian hari.
E. Eksplorasi & Pencarian
•	Halaman Beranda Dinamis: Menampilkan listing ikan cupang terbaru dan kategori unggulan dalam tata letak grid produk.
•	Pencarian & Filter: Pembeli dapat mencari produk berdasarkan nama, jenis ekor (kategori), rentang harga, atau lokasi penjual.
5. Arsitektur Database (Rencana Skema)
Berikut adalah entitas utama yang direncanakan dalam database MySQL:
•	users: Menyimpan kredensial pengguna, role (pembeli/penjual), profil, dan informasi dasar.
•	stores (toko): Menyimpan informasi toko milik penjual (user_id, nama_toko, deskripsi, lokasi).
•	categories: Menyimpan kategori jenis ekor ikan cupang (nama_kategori, deskripsi).
•	products (produk ikan cupang): Menyimpan detail listing ikan (store_id, category_id, nama, harga, stok, foto, deskripsi, umur).
•	carts (keranjang): Tabel pivot/keranjang sementara milik pembeli (user_id, product_id, jumlah).
•	orders (pesanan): Menyimpan data transaksi (user_id, store_id, total_harga, status, alamat_kirim).
•	order_items: Tabel pivot rincian produk dalam satu pesanan (order_id, product_id, jumlah, subtotal).
•	reviews (ulasan): Menyimpan rating dan komentar pembeli pada produk (user_id, product_id, rating, komentar).
6. Antarmuka Pengguna & UX (User Experience)
•	Grid Layout Produk: Halaman beranda dan etalase menggunakan grid responsif untuk menampilkan listing ikan cupang dengan foto, nama, dan harga.
•	Desain Responsif: Tampilan optimal saat dibuka di perangkat mobile maupun desktop.
•	SweetAlert Alers: Dialog pop-up modern untuk aksi krusial, seperti konfirmasi sebelum menghapus produk/pesanan dan notifikasi keberhasilan transaksi.
7. Pembagian Tugas Anar Anggota
Berikut adalah pembagian peran dan tanggung jawab pengembangan fitur dalam project ini:
1. Augitan Alba Setiaji (NIM: 241120110) — Ketua
Peran: Backend Architect & Lead Developer
•	Merancang dan memigrasikan database (users, stores, categories, products, carts, orders, order_items, reviews).
•	Mengembangkan sistem autentikasi dan manajemen role (Pembeli/Penjual) menggunakan middleware kustom.
•	Koordinasi integrasi kode antar anggota kelompok.
•	Membuat dan mendesain landing page aplikasi (Beranda).
•	Melakukan deployment sistem ke production server (Hostinger).
•	Melakukan pengujian kualitas fungsionalitas dan performa aplikasi (Quality Assurance & Control).
•	Memastikan keamanan proses routing, validasi, dan upload file.
2. Hendri Setyawan (NIM: 241110041)
Peran: UI/UX Designer & Frontend Auth Developer
•	Mengintegrasikan styling dasar (Tailwind CSS/Bootstrap, ikon, SweetAlert2) dengan palette warna brand BettaMarket.
•	Membuat template halaman master (layout utama/app.blade.php).
•	Mendesain dan mengimplementasikan UI untuk halaman Login, Register, dan Edit Profil.
3. Danisa Arif Nugroho (NIM: 241110032)
Peran: Modul Produk & Etalase Developer
•	Mengembangkan controller dan logika CRUD untuk entitas Produk (ProductController), termasuk penanganan unggah foto ikan cupang.
•	Mendesain dan mengimplementasikan UI untuk halaman daftar produk, detail produk, tambah produk, dan edit produk.
•	Mengembangkan fitur kategori (jenis ekor) dan filter pencarian produk.
4. Nadi Supriadi (NIM: 241110054)
Peran: Modul Transaksi & Interaksi Developer
•	Mengembangkan fitur keranjang belanja, checkout, dan riwayat pesanan (OrderController, CartController).
•	Mengembangkan fitur ulasan/rating produk dan wishlist.
•	Mendesain dan mengimplementasikan UI halaman keranjang, checkout, dan riwayat transaksi.
7. Aturan Caching, Lazy Loading, & Pagination
•	Caching: Implementasikan caching (misalnya caching query kategori atau data statis) untuk mempercepat waktu respons aplikasi dan meminimalkan beban database.
•	Lazy Loading: Terapkan lazy loading pada aset gambar produk (misalnya pada grid etalase) untuk mengurangi waktu pemuatan halaman awal dan menghemat bandwidth.
•	Pagination: Gunakan pagination untuk membatasi jumlah data yang ditampilkan sekaligus dalam satu halaman (misalnya pada halaman pencarian, etalase, atau riwayat pesanan) agar performa aplikasi tetap optimal.
8. Aturan Aset & Struktur Kode
Penyimpanan Aset Gambar:
•	Semua foto produk ikan cupang yang diunggah harus disimpan ke folder storage/app/public/products.
•	Semua foto profil pengguna yang diunggah harus disimpan ke folder storage/app/public/profile-pictures.
Pemisahan Struktur Kode:
•	Penulisan kode harus memisahkan struktur HTML (Blade), CSS, dan JS secara terpisah.
•	File CSS dan JS kustom harus ditempatkan di folder public/assets.
9. Batasan
•	Dilarang menggunakan, menambah, atau mengubah fitur yang tidak dijtentukan pada rancangan di atas.

Pembagian Peran Tugas

Augitian Alba Setiaji : Backend Architect & Lead Developer	Database, autentikasi, koordinasi tim, landing page, deployment, QA, keamanan

Hendri Setyawan	: UI/UX Designer & Frontend Auth Developer	Styling dasar, layout master, UI Login/Register/Edit Profil

Danisa Arif Nugroho	: Modul Produk & Etalase Developer	CRUD produk, upload foto, kategori jenis ekor, filter pencarian

Nadi Supriadi : Modul Transaksi & Interaksi Developer	Keranjang, checkout, riwayat pesanan, ulasan, wishlist

