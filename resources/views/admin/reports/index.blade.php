<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Laporan & Ekspor Data') }}
        </h2>
    </x-slot>

    {{-- Form Generator --}}
    <div class="card mb-4">
        <div class="card-header">Generate Laporan</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.generate') }}" id="reportForm">
                <div class="row g-3 align-items-start">
                    {{-- Jenis Laporan --}}
                    <div class="col-md-3">
                        <label for="report_type" class="form-label">Jenis Laporan <span class="text-danger">*</span></label>
                        <select name="report_type" id="report_type" class="form-select @error('report_type') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="visitors" {{ (isset($request['report_type']) && $request['report_type'] == 'visitors') || old('report_type') == 'visitors' ? 'selected' : '' }}>Jumlah Pengunjung</option>
                            <option value="revenue" {{ (isset($request['report_type']) && $request['report_type'] == 'revenue') || old('report_type') == 'revenue' ? 'selected' : '' }}>Total Pemasukan</option>
                            <option value="facilities" {{ (isset($request['report_type']) && $request['report_type'] == 'facilities') || old('report_type') == 'facilities' ? 'selected' : '' }}>Fasilitas Terpopuler</option>
                        </select>
                         @error('report_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Rentang Waktu --}}
                    <div class="col-md-3">
                        <label for="date_range_type" class="form-label">Rentang Waktu <span class="text-danger">*</span></label>
                        <select name="date_range_type" id="date_range_type" class="form-select @error('date_range_type') is-invalid @enderror" required>
                            <option value="daily" {{ (isset($request['date_range_type']) && $request['date_range_type'] == 'daily') || old('date_range_type', 'daily') == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ (isset($request['date_range_type']) && $request['date_range_type'] == 'weekly') || old('date_range_type') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ (isset($request['date_range_type']) && $request['date_range_type'] == 'monthly') || old('date_range_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="custom" {{ (isset($request['date_range_type']) && $request['date_range_type'] == 'custom') || old('date_range_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('date_range_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Input Tanggal Spesifik (muncul kondisional) --}}
                    <div class="col-md-3" id="specific_date_col" style="{{ (isset($request['date_range_type']) && $request['date_range_type'] != 'daily') ? 'display: none;' : '' }}">
                        <label for="specific_date" class="form-label">Pilih Tanggal</label>
                        <input type="date" name="specific_date" id="specific_date" class="form-control @error('specific_date') is-invalid @enderror" value="{{ $request['specific_date'] ?? old('specific_date', now()->format('Y-m-d')) }}">
                         @error('specific_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-3" id="specific_week_col" style="{{ (!isset($request['date_range_type']) || $request['date_range_type'] != 'weekly') ? 'display: none;' : '' }}">
                        <label for="specific_week" class="form-label">Pilih Minggu</label>
                        <input type="week" name="specific_week" id="specific_week" class="form-control @error('specific_week') is-invalid @enderror" value="{{ $request['specific_week'] ?? old('specific_week', now()->format('Y-\WW')) }}">
                         @error('specific_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-3" id="specific_month_col" style="{{ (!isset($request['date_range_type']) || $request['date_range_type'] != 'monthly') ? 'display: none;' : '' }}">
                        <label for="specific_month" class="form-label">Pilih Bulan</label>
                        <input type="month" name="specific_month" id="specific_month" class="form-control @error('specific_month') is-invalid @enderror" value="{{ $request['specific_month'] ?? old('specific_month', now()->format('Y-m')) }}">
                         @error('specific_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Input Custom Range (muncul kondisional) --}}
                    <div class="col-md-3" id="custom_start_col" style="{{ (!isset($request['date_range_type']) || $request['date_range_type'] != 'custom') ? 'display: none;' : '' }}">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ $request['start_date'] ?? old('start_date') }}">
                         @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-3" id="custom_end_col" style="{{ (!isset($request['date_range_type']) || $request['date_range_type'] != 'custom') ? 'display: none;' : '' }}">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ $request['end_date'] ?? old('end_date') }}">
                         @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Filter Destinasi --}}
                    <div class="col-md-3">
                        <label for="destination_id" class="form-label">Destinasi (Opsional)</label>
                        <select name="destination_id" id="destination_id" class="form-select @error('destination_id') is-invalid @enderror">
                            <option value="">Semua Destinasi</option>
                            @foreach($destinations as $id => $name)
                                <option value="{{ $id }}" {{ (isset($request['destination_id']) && $request['destination_id'] == $id) || old('destination_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                         @error('destination_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Format Output --}}
                     <div class="col-md-3">
                        <label for="format" class="form-label">Format Output <span class="text-danger">*</span></label>
                        <select name="format" id="format" class="form-select @error('format') is-invalid @enderror" required>
                            <option value="screen" {{ (isset($request['format']) && $request['format'] == 'screen') || old('format', 'screen') == 'screen' ? 'selected' : '' }}>Tampilkan di Layar</option>
                            <option value="pdf" {{ (isset($request['format']) && $request['format'] == 'pdf') || old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="excel" {{ (isset($request['format']) && $request['format'] == 'excel') || old('format') == 'excel' ? 'selected' : '' }}>Excel</option>
                        </select>
                        @error('format') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-cogs"></i> Generate Laporan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Hasil Laporan (jika format = 'screen') --}}
    @if(isset($reportData) && isset($request['format']) && $request['format'] == 'screen')
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Hasil: {{ $reportTitle }}</span>
                <div>
                    {{-- Tombol Ekspor jika ditampilkan di layar --}}
                    <a href="{{ route('admin.reports.generate', array_merge($request, ['format' => 'pdf'])) }}" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> Ekspor PDF</a>
                    <a href="{{ route('admin.reports.generate', array_merge($request, ['format' => 'excel'])) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Ekspor Excel</a>
                </div>
            </div>
            <div class="card-body">
                <p>
                    <strong>Periode:</strong> {{ $startDate->isoFormat('D MMMM YYYY') }} s/d {{ $endDate->isoFormat('D MMMM YYYY') }} <br>
                    @if($destination)
                        <strong>Destinasi:</strong> {{ $destination->name }}
                    @endif
                </p>

                @if($reportData->isEmpty())
                    <div class="alert alert-warning">Tidak ada data ditemukan untuk kriteria yang dipilih.</div>
                @else
                    {{-- Include view tabel parsial berdasarkan jenis laporan --}}
                    @php
                        $viewToInclude = 'admin.reports._table_' . ($request['report_type'] ?? 'default');
                    @endphp
                     @include($viewToInclude, ['reportData' => $reportData])
                @endif
            </div>
        </div>
    @endif

     {{-- Include Font Awesome if you use icons --}}
    @push('scripts')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

     <script>
         // Script untuk show/hide input tanggal berdasarkan range type
         const dateRangeSelect = document.getElementById('date_range_type');
         const specificDateCol = document.getElementById('specific_date_col');
         const specificWeekCol = document.getElementById('specific_week_col');
         const specificMonthCol = document.getElementById('specific_month_col');
         const customStartCol = document.getElementById('custom_start_col');
         const customEndCol = document.getElementById('custom_end_col');

         function toggleDateInputs() {
             const selectedType = dateRangeSelect.value;
             specificDateCol.style.display = selectedType === 'daily' ? 'block' : 'none';
             specificWeekCol.style.display = selectedType === 'weekly' ? 'block' : 'none';
             specificMonthCol.style.display = selectedType === 'monthly' ? 'block' : 'none';
             customStartCol.style.display = selectedType === 'custom' ? 'block' : 'none';
             customEndCol.style.display = selectedType === 'custom' ? 'block' : 'none';
         }

         dateRangeSelect.addEventListener('change', toggleDateInputs);
         // Panggil saat load untuk inisialisasi
         // toggleDateInputs(); // Dihapus agar state dari request sebelumnya tetap terjaga
     </script>
    @endpush
</x-admin-layout>