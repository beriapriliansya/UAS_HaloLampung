{{-- resources/views/admin/destinations/edit.blade.php --}}

<x-admin-layout> {{-- Sesuaikan jika Anda menggunakan @extends --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                {{ __('Edit Destinasi: ') }} {{ $destination->name }}
            </h2>
            <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Tampilkan error validasi global jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.destinations.update', $destination->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf {{-- Token Keamanan Laravel --}}
                @method('PUT') {{-- Method Spoofing untuk request PUT --}}

                {{-- Nama Destinasi --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Destinasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $destination->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    {{-- Disarankan menggunakan text editor WYSIWYG seperti TinyMCE atau CKEditor di sini untuk pengalaman yang lebih baik --}}
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="5" required>{{ old('description', $destination->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga Tiket & Kuota --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="base_ticket_price" class="form-label">Harga Tiket Dasar (Rp) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="1000"
                            class="form-control @error('base_ticket_price') is-invalid @enderror" id="base_ticket_price"
                            name="base_ticket_price"
                            value="{{ old('base_ticket_price', $destination->base_ticket_price) }}" required
                            min="0">
                        @error('base_ticket_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="visitor_quota" class="form-label">Kuota Pengunjung (per hari/sesi)</label>
                        <input type="number" class="form-control @error('visitor_quota') is-invalid @enderror"
                            id="visitor_quota" name="visitor_quota"
                            value="{{ old('visitor_quota', $destination->visitor_quota) }}" min="0"
                            placeholder="Kosongkan jika tidak terbatas">
                        @error('visitor_quota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Lokasi & Jam Operasional --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                            id="location" name="location" value="{{ old('location', $destination->location) }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="operating_hours" class="form-label">Jam Operasional</label>
                        <input type="text" class="form-control @error('operating_hours') is-invalid @enderror"
                            id="operating_hours" name="operating_hours"
                            value="{{ old('operating_hours', $destination->operating_hours) }}"
                            placeholder="Contoh: 08:00 - 17:00 WIB">
                        @error('operating_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Foto Utama --}}
                <div class="mb-3">
                    <label class="form-label d-block">Foto Utama Saat Ini:</label>
                    @if ($destination->main_photo)
                        <img src="{{ $destination->main_photo_url }}" alt="{{ $destination->name }}" width="200"
                            class="img-thumbnail mb-2">
                    @else
                        <span class="text-muted fst-italic">Tidak ada foto utama.</span>
                    @endif

                    <label for="main_photo" class="form-label mt-2">Ganti Foto Utama (Opsional)</label>
                    <input type="file" class="form-control @error('main_photo') is-invalid @enderror" id="main_photo"
                        name="main_photo" accept="image/jpeg,image/png,image/gif,image/webp">
                    @error('main_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG,
                        GIF, WEBP. Maks: 2MB.</small>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-save me-1" viewBox="0 0 16 16">
                            <path
                                d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v4.5h2a.5.5 0 0 1 .354.854l-2.5 2.5a.5.5 0 0 1-.708 0l-2.5-2.5A.5.5 0 0 1 5.5 6.5h2V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z" />
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Batal</a>
                </div>

            </form>
            <hr class="my-4">

            {{-- Bagian Manajemen Fasilitas untuk Destinasi Ini --}}
            <h4 class="mb-3">Kelola Fasilitas untuk {{ $destination->name }}</h4>

            {{-- Form untuk Menambahkan Fasilitas Baru ke Destinasi --}}
            <div class="card mb-4">
                <div class="card-header">Tambah Fasilitas Tersedia</div>
                <div class="card-body">
                    {{-- Kita butuh route & controller baru untuk handle ini --}}
                    <form action="{{ route('admin.destinations.facilities.attach', $destination) }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="facility_id" class="form-label">Pilih Fasilitas</label>
                                <select name="facility_id" id="facility_id"
                                    class="form-select @error('facility_id', 'attachFacility') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Fasilitas --</option>
                                    @foreach ($availableFacilities as $facility)
                                        <option value="{{ $facility->id }}"
                                            {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                            {{ $facility->name }}</option>
                                    @endforeach
                                </select>
                                @error('facility_id', 'attachFacility')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="facility_price" class="form-label">Harga Tambahan (Rp)</label>
                                <input type="number" step="1000" name="price" id="facility_price"
                                    class="form-control @error('price', 'attachFacility') is-invalid @enderror"
                                    value="{{ old('price', 0) }}" required min="0">
                                @error('price', 'attachFacility')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="facility_quota" class="form-label">Kuota <small>(Kosongkan jika tak
                                        terbatas)</small></label>
                                <input type="number" name="quota" id="facility_quota"
                                    class="form-control @error('quota', 'attachFacility') is-invalid @enderror"
                                    value="{{ old('quota') }}" min="0">
                                @error('quota', 'attachFacility')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">Tambahkan</button>
                            </div>
                        </div>
                        {{-- Menampilkan error umum dari form attach --}}
                        @if ($errors->attachFacility->any())
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->attachFacility->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Daftar Fasilitas yang Sudah Terhubung --}}
            <div class="card">
                <div class="card-header">Fasilitas Terhubung</div>
                <div class="card-body">
                    @if ($linkedFacilities->isEmpty())
                        <p class="text-muted">Belum ada fasilitas yang terhubung ke destinasi ini.</p>
                    @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Fasilitas</th>
                                    <th>Harga</th>
                                    <th>Kuota</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($linkedFacilities as $facility)
                                    <tr>
                                        {{-- Form terpisah untuk setiap row agar update/delete spesifik --}}
                                        <form
                                            action="{{ route('admin.destinations.facilities.update', ['destination' => $destination, 'facility' => $facility->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <td>{{ $facility->name }}</td>
                                            <td>
                                                <input type="number" step="1000" name="price"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('price', $facility->pivot->price) }}" required
                                                    min="0" style="width: 120px;">
                                            </td>
                                            <td>
                                                <input type="number" name="quota"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('quota', $facility->pivot->quota) }}"
                                                    min="0" style="width: 100px;" placeholder="Kosong = ∞">
                                            </td>
                                            <td>
                                                <select name="is_available" class="form-select form-select-sm"
                                                    style="width: 120px;">
                                                    <option value="1"
                                                        {{ $facility->pivot->is_available ? 'selected' : '' }}>Tersedia
                                                    </option>
                                                    <option value="0"
                                                        {{ !$facility->pivot->is_available ? 'selected' : '' }}>Tidak
                                                        Tersedia</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form> {{-- Tutup form update --}}
                                        {{-- Form untuk detach (hapus relasi) --}}
                                        <form
                                            action="{{ route('admin.destinations.facilities.detach', ['destination' => $destination, 'facility' => $facility->id]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin hapus fasilitas ini dari destinasi?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ms-1">Hapus</button>
                                        </form>
                                        </td>
                                    </tr>
                                    {{-- Tampilkan error validasi per baris jika ada (lebih kompleks, mungkin perlu session flash per item) --}}
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Opsional: Tambahkan script untuk preview gambar sebelum upload jika diinginkan --}}
    {{-- @push('scripts')
    <script>
        document.getElementById('main_photo').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                // Anda bisa menambahkan preview di sini jika mau
                // const preview = document.getElementById('image-preview'); // Tambahkan elemen img dengan id ini di atas
                // preview.src = URL.createObjectURL(file);
                // preview.style.display = 'block';
            }
        });
    </script>
    @endpush --}}

</x-admin-layout>
