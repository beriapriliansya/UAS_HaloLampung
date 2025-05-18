{{-- File: resources/views/admin/facilities/edit.blade.php --}}

<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{-- Tampilkan nama fasilitas yang sedang diedit untuk kejelasan --}}
            {{ __('Edit Fasilitas Master: ') }} {{ $facility->name }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            {{-- Form mengarah ke route 'admin.facilities.update' dengan method POST --}}
            {{-- Route 'update' memerlukan parameter $facility --}}
            <form action="{{ route('admin.facilities.update', $facility) }}" method="POST">
                @csrf {{-- Token CSRF --}}
                @method('PUT') {{-- Method Spoofing untuk request PUT/PATCH --}}

                {{-- Input untuk Nama Fasilitas --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        {{-- Tampilkan input lama jika ada, jika tidak, tampilkan data saat ini dari $facility --}}
                        value="{{ old('name', $facility->name) }}"
                        required
                        placeholder="Contoh: Guide Lokal, Antar Jemput Bandara">

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
                        placeholder="Jelaskan sedikit tentang fasilitas ini...">{{ old('description', $facility->description) }}</textarea> {{-- Tampilkan input lama atau data saat ini --}}

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Fasilitas</button> {{-- Ganti teks tombol --}}
                </div>

            </form>
        </div>
    </div>
</x-admin-layout>