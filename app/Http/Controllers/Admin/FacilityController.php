<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk unique rule saat update

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar fasilitas master.
     */
    public function index()
    {
        // Ambil data fasilitas, urutkan berdasarkan yang terbaru, paginasi 10 per halaman
        $facilities = Facility::latest()->paginate(10);

        // Kirim data ke view
        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat fasilitas baru.
     */
    public function create()
    {
        // Cukup tampilkan view form create
        return view('admin.facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan fasilitas baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name', // Nama wajib diisi, unik di tabel facilities
            'description' => 'nullable|string', // Deskripsi opsional
        ]);

        // 2. Buat data baru di database
        Facility::create($validated);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.facilities.index')
                         ->with('success', 'Fasilitas master berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Kita tidak menggunakan halaman show untuk master fasilitas)
     */
    public function show(Facility $facility)
    {
        // Biasanya tidak diperlukan untuk master data sederhana di admin
        // Jika dibutuhkan, return view('admin.facilities.show', compact('facility'));
        abort(404); // Atau kembalikan 404 Not Found
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit fasilitas.
     */
    public function edit(Facility $facility)
    {
        // View menerima data $facility dari Route Model Binding
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data fasilitas di database.
     */
    public function update(Request $request, Facility $facility)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            // Nama wajib, unik, tapi abaikan pemeriksaan unik untuk dirinya sendiri
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities')->ignore($facility->id),
            ],
            'description' => 'nullable|string',
        ]);

        // 2. Update data di database
        $facility->update($validated);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.facilities.index')
                         ->with('success', 'Fasilitas master berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus data fasilitas dari database.
     */
    public function destroy(Facility $facility)
    {
        // Hapus data dari database
        // Relasi di tabel pivot 'destination_facility' akan otomatis terhapus
        // karena kita menggunakan onDelete('cascade') di migration pivot.
        $facility->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.facilities.index')
                         ->with('success', 'Fasilitas master berhasil dihapus.');
    }
}