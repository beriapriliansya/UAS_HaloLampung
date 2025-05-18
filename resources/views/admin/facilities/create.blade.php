{{-- File: resources/views/admin/facilities/create.blade.php --}}

<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Tambah Fasilitas Master Baru') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            {{-- Form mengarah ke route 'admin.facilities.store' dengan method POST --}}
            <form action="{{ route('admin.facilities.store') }}" method="POST">
                @csrf {{-- Token CSRF untuk keamanan --}}

                {{-- Input untuk Nama Fasilitas --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror" {{-- Tambah class is-invalid jika ada error --}}
                        id="name"
                        name="name"
                        value="{{ old('name') }}" {{-- Tampilkan kembali input lama jika validasi gagal --}}
                        required {{-- Validasi dasar di sisi client --}}
                        autofocus {{-- Fokus otomatis ke field ini saat halaman load --}}
                        placeholder="Contoh: Guide Lokal, Antar Jemput Bandara">

                    {{-- Menampilkan pesan error validasi untuk 'name' --}}
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Input untuk Deskripsi (Opsional) --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi (Opsional)</label>
                    <textarea
                        class="form-control @error('description') is-invalid @enderror"
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Jelaskan sedikit tentang fasilitas ini...">{{ old('description') }}</textarea> {{-- Tampilkan kembali input lama --}}

                     {{-- Menampilkan pesan error validasi untuk 'description' --}}
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Fasilitas</button>
                </div>

            </form>
        </div>
    </div>
</x-admin-layout>