@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-custom p-5">
                <h3 class="fw-bold mb-4"><i class="fas fa-edit text-primary me-2"></i> Edit Ikan Cupang</h3>
                
                <form action="{{ route('products.update', $id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Gambar Ikan</label>
                        <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('productImage').click()" 
                             style="{{ !empty($product['imageBase64']) ? 'border-style: solid; border-color: #667eea; padding: 10px;' : '' }}">
                            <input type="file" name="image" id="productImage" accept="image/*" style="display:none;" onchange="previewImage(event)">
                            
                            @if(!empty($product['imageBase64']))
                                <img id="imagePreview" src="{{ $product['imageBase64'] }}" class="upload-preview">
                                <div id="uploadPlaceholder" class="d-none">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <p class="text-muted mb-1">Klik untuk ganti gambar</p>
                                    <p class="text-muted small">Format: JPG, PNG. Max 2MB</p>
                                </div>
                            @else
                                <div id="uploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <p class="text-muted mb-1">Klik untuk upload gambar ikan</p>
                                    <p class="text-muted small">Format: JPG, PNG. Max 2MB</p>
                                </div>
                                <img id="imagePreview" class="upload-preview d-none">
                            @endif
                        </div>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Ikan</label>
                        <input type="text" name="name" class="form-control form-control-custom" placeholder="Contoh: Cupang Halfmoon Red Dragon" required value="{{ old('name', $product['name'] ?? '') }}">
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Jenis / Kategori</label>
                            <select name="category" class="form-select form-control-custom" required>
                                <option value="Halfmoon" {{ (old('category', $product['category'] ?? '') == 'Halfmoon') ? 'selected' : '' }}>Halfmoon</option>
                                <option value="Plakat" {{ (old('category', $product['category'] ?? '') == 'Plakat') ? 'selected' : '' }}>Plakat</option>
                                <option value="Crowntail" {{ (old('category', $product['category'] ?? '') == 'Crowntail') ? 'selected' : '' }}>Crowntail</option>
                                <option value="Double Tail" {{ (old('category', $product['category'] ?? '') == 'Double Tail') ? 'selected' : '' }}>Double Tail</option>
                                <option value="Super Delta" {{ (old('category', $product['category'] ?? '') == 'Super Delta') ? 'selected' : '' }}>Super Delta</option>
                                <option value="Lainnya" {{ (old('category', $product['category'] ?? '') == 'Lainnya') ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control form-control-custom" placeholder="150000" required min="0" value="{{ old('price', $product['price'] ?? 0) }}">
                            @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Deskripsi</label>
                        <textarea name="description" class="form-control form-control-custom" rows="4" placeholder="Deskripsikan ikan cupang Anda..." required>{{ old('description', $product['description'] ?? '') }}</textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary flex-fill">Batal</a>
                        <button type="submit" class="btn btn-gradient flex-fill"><i class="fas fa-save me-2"></i> Update Produk</button>
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
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
            document.getElementById('uploadPlaceholder').classList.add('d-none');
            document.getElementById('imageUploadArea').style.borderStyle = 'solid';
            document.getElementById('imageUploadArea').style.borderColor = '#667eea';
            document.getElementById('imageUploadArea').style.padding = '10px';
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
@endsection