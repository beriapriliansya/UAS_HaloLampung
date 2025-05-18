<table class="table table-sm table-striped table-bordered">
    <thead>
        <tr>
            <th>Tanggal Pembayaran</th>
            <th>Destinasi</th>
            <th>Total Pemasukan (Rp)</th>
        </tr>
    </thead>
    <tbody>
         @php $grandTotal = 0; @endphp
        @forelse ($reportData as $data)
            <tr>
                 <td>{{ \Carbon\Carbon::parse($data->date)->isoFormat('dddd, D MMM YYYY') }}</td>
                 <td>{{ $data->destination_name ?? 'Semua Destinasi' }}</td> {{-- Sesuaikan jika nama diambil --}}
                 <td class="text-end">{{ number_format($data->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $data->total_revenue; @endphp
        @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
    @if(!$reportData->isEmpty())
    <tfoot>
        <tr>
            <th colspan="2" class="text-end">Total Keseluruhan:</th>
            <th class="text-end">{{ number_format($grandTotal, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
    @endif
</table>