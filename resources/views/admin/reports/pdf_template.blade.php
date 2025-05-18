<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $reportTitle }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        h2, h4 { margin-bottom: 5px; }
        p { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>{{ $reportTitle }}</h2>
    <p>
        <strong>Periode:</strong> {{ $startDate->isoFormat('D MMMM YYYY') }} s/d {{ $endDate->isoFormat('D MMMM YYYY') }} <br>
        @if($destination)
            <strong>Destinasi:</strong> {{ $destination->name }}
        @endif
    </p>

    @if($reportData->isEmpty())
        <p>Tidak ada data ditemukan untuk kriteria yang dipilih.</p>
    @else
        {{-- Include view tabel parsial --}}
         @php
             $viewToInclude = 'admin.reports._table_' . ($request['report_type'] ?? 'default');
         @endphp
         @include($viewToInclude, ['reportData' => $reportData])
    @endif

     <p style="margin-top: 20px; font-size: 8px; color: #888;">
         Laporan ini digenerate pada: {{ now()->isoFormat('D MMMM YYYY, HH:mm:ss') }}
     </p>

</body>
</html>