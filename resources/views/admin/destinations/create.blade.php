<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Tambah Destinasi Baru') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Destinasi</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                 <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="base_ticket_price" class="form-label">Harga Tiket Dasar (Rp)</label>
                        <input type="number" step="1000" class="form-control @error('base_ticket_price') is-invalid @enderror" id="base_ticket_price" name="base_ticket_price" value="{{ old('base_ticket_price') }}" required min="0">
                        @error('base_ticket_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="visitor_quota" class="form-label">Kuota Pengunjung (per hari/sesi) <small>(Kosongkan jika tidak terbatas)</small></label>
                        <input type="number" class="form-control @error('visitor_quota') is-invalid @enderror" id="visitor_quota" name="visitor_quota" value="{{ old('visitor_quota') }}" min="0">
                        @error('visitor_quota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                 </div>

                 <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="operating_hours" class="form-label">Jam Operasional</label>
                        <input type="text" class="form-control @error('operating_hours') is-invalid @enderror" id="operating_hours" name="operating_hours" value="{{ old('operating_hours') }}">
                        @error('operating_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                 </div>


                <div class="mb-3">
                    <label for="main_photo" class="form-label">Foto Utama</label>
                    <input type="file" class="form-control @error('main_photo') is-invalid @enderror" id="main_photo" name="main_photo" accept="image/*">
                     @error('main_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                     <small class="form-text text-muted">Format: JPG, PNG, GIF, WEBP. Maks: 2MB.</small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-admin-layout>