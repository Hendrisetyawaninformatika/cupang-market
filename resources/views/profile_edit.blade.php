@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-user-edit text-success me-2"></i> Edit Profil</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-6">
            <div class="card-custom p-4">
                <form action="{{ route('profile.update', $seller['id'] ?? 1) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @if(!empty($seller['photo']))
                                <img src="{{ $seller['photo'] }}" id="previewPhoto" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;border:4px solid #0d6efd;">
                            @else
                                <div id="previewPhoto" class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:120px;height:120px;border:4px solid #0d6efd;">
                                    <i class="fas fa-user fa-3x text-primary"></i>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <label for="photo" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-camera me-2"></i> Ganti Foto
                            </label>
                            <input type="file" name="photo" id="photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                        </div>
                        @error('photo')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', $seller['name'] ?? '') }}" placeholder="Masukkan nama lengkap" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label fw-bold"><i class="fab fa-whatsapp text-success me-1"></i> Nomor WhatsApp</label>
                        <input type="text" name="whatsapp" id="whatsapp" class="form-control form-control-lg @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $seller['whatsapp'] ?? '') }}" placeholder="Contoh: 08123456789" required>
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nomor ini akan ditampilkan ke pembeli untuk menghubungi kamu.</small>
                    </div>

                    <!-- Email (readonly) -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" id="email" class="form-control" value="{{ $seller['email'] ?? '' }}" readonly disabled>
                        <small class="text-muted">Email tidak dapat diubah.</small>
                    </div>

                    <!-- Bio / Deskripsi Toko -->
                    <div class="mb-4">
                        <label for="bio" class="form-label fw-bold">Bio / Deskripsi Toko</label>
                        <textarea name="bio" id="bio" rows="3" class="form-control @error('bio') is-invalid @enderror" placeholder="Ceritakan sedikit tentang toko kamu...">{{ old('bio', $seller['bio'] ?? '') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg flex-fill"><i class="fas fa-save me-2"></i> Simpan Perubahan</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('previewPhoto');
            if(preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Replace div with img
                var img = document.createElement('img');
                img.src = e.target.result;
                img.id = 'previewPhoto';
                img.className = 'rounded-circle';
                img.style = 'width:120px;height:120px;object-fit:cover;border:4px solid #0d6efd;';
                preview.parentNode.replaceChild(img, preview);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
