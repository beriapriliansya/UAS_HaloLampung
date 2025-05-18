<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                {{ __('Manajemen Destinasi') }}
            </h2>
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">Tambah Destinasi</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Harga Tiket</th>
                        <th>Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($destinations as $destination)
                        <tr>
                            <td>{{ $loop->iteration + $destinations->firstItem() - 1 }}</td>
                             <td>
                                 <img src="{{ $destination->main_photo_url }}" alt="{{ $destination->name }}" width="80" class="img-thumbnail">
                             </td>
                            <td>{{ $destination->name }}</td>
                            <td>Rp {{ number_format($destination->base_ticket_price, 0, ',', '.') }}</td>
                            <td>{{ $destination->visitor_quota ?? 'Tidak terbatas' }}</td>
                            <td>
                                <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-sm btn-warning">Edit</a>
                                {{-- Tambahkan link ke detail jika ada --}}
                                {{-- <a href="{{ route('admin.destinations.show', $destination) }}" class="btn btn-sm btn-info">Detail</a> --}}

                                {{-- Tombol Hapus dengan Konfirmasi --}}
                                <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus destinasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data destinasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $destinations->links() }} {{-- Tampilkan Paginasi --}}
        </div>
    </div>
</x-admin-layout>