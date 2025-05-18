<x-admin-layout>
    <x-slot name="header">
         <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                {{ __('Master Fasilitas') }}
            </h2>
            <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">Tambah Fasilitas</a>
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
                        <th>Nama Fasilitas</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                 <tbody>
                    @forelse ($facilities as $facility)
                        <tr>
                            <td>{{ $loop->iteration + $facilities->firstItem() - 1 }}</td>
                            <td>{{ $facility->name }}</td>
                            <td>{{ $facility->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus fasilitas ini? Ini juga akan menghapusnya dari semua destinasi.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data fasilitas master.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="card-footer">
            {{ $facilities->links() }}
        </div>
    </div>
</x-admin-layout>