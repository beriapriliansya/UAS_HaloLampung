<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                {{ __('Manajemen Berita') }}
            </h2>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Tambah Berita Baru</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Tgl Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($newsItems as $item)
                            <tr>
                                <td>{{ $loop->iteration + $newsItems->firstItem() - 1 }}</td>
                                <td>
                                    <img src="{{ $item->featured_image_url }}" alt="{{ $item->title }}" width="80" class="img-thumbnail">
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->author->name ?? 'N/A' }}</td>
                                <td>
                                    @if($item->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $item->published_at ? $item->published_at->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data berita.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $newsItems->links() }}
        </div>
    </div>
</x-admin-layout>