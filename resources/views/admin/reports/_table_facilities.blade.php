<table class="table table-sm table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Fasilitas</th>
            <th>Jumlah Dipesan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reportData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->facility_name }}</td>
                <td class="text-end">{{ number_format($data->total_ordered, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>