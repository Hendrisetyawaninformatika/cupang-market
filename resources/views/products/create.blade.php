@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-custom p-5">
                <h3 class="fw-bold mb-4"><i class="fas fa-plus-circle text-primary me-2"></i> Tambah Ikan Cupang</h3>
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    
                    {{-- ⬇️ TAMBAHKAN HIDDEN INPUT INI --}}
                    <input type="hidden" name="imageBase64" id="imageBase64">
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Gambar Ikan <small class="text-muted">(Opsional, Max 2MB)</small></label>
                        <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('productImage').click()">
                            <input type="file" name="image" id="productImage" accept="image/jpeg,image/png,image/jpg" style="display:none;" onchange="previewImage(event)">
                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                <p class="text-muted mb-1">Klik untuk upload gambar ikan</p>
                                <p class="text-muted small">Format: JPG, PNG. Max 2MB</p>
                            </div>
                            <img id="imagePreview" class="upload-preview d-none">
                        </div>
                        @error('image')
                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Ikan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror" placeholder="Contoh: Cupang Halfmoon Red Dragon" required value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Jenis / Kategori <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-control-custom @error('category') is-invalid @enderror" required>
                                <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori</option>
                                <option value="Halfmoon" {{ old('category') == 'Halfmoon' ? 'selected' : '' }}>Halfmoon</option>
                                <option value="Plakat" {{ old('category') == 'Plakat' ? 'selected' : '' }}>Plakat</option>
                                <option value="Crowntail" {{ old('category') == 'Crowntail' ? 'selected' : '' }}>Crowntail</option>
                                <option value="Double Tail" {{ old('category') == 'Double Tail' ? 'selected' : '' }}>Double Tail</option>
                                <option value="Super Delta" {{ old('category') == 'Super Delta' ? 'selected' : '' }}>Super Delta</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control form-control-custom @error('price') is-invalid @enderror" placeholder="150000" required min="0" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control form-control-custom @error('description') is-invalid @enderror" rows="4" placeholder="Deskripsikan ikan cupang Anda..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('home') }}" class="btn btn-secondary flex-fill">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-gradient flex-fill" id="submitBtn">
                            <i class="fas fa-save me-2"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Validasi ukuran
        if (file.size > 2 * 1024 * 1024) {
            alert('Gambar terlalu besar! Maksimal 2MB');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Tampilkan preview
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
            document.getElementById('uploadPlaceholder').classList.add('d-none');
            document.getElementById('imageUploadArea').style.borderStyle = 'solid';
            document.getElementById('imageUploadArea').style.borderColor = '#667eea';
            
            // ⬇️ INI YANG PENTING: Simpan Base64 ke hidden input
            document.getElementById('imageBase64').value = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Disable tombol saat submit
    document.getElementById('productForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
    });
</script>
@endsection
@endsection