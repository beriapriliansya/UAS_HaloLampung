<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk file upload/delete
use Illuminate\Support\Str; // Untuk slug jika tidak di model
use App\Models\Facility;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::latest()->paginate(10); // Ambil data & paginasi
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'base_ticket_price' => 'required|numeric|min:0',
            'visitor_quota' => 'nullable|integer|min:0',
            'main_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validasi gambar
        ]);

        // Handle File Upload
        if ($request->hasFile('main_photo')) {
            $path = $request->file('main_photo')->store('destinations', 'public'); // Simpan di storage/app/public/destinations
            $validated['main_photo'] = $path;
        }

        // Buat slug (jika tidak otomatis di model)
        // $validated['slug'] = Str::slug($validated['name']);
        // Handle keunikan slug jika perlu

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
                         ->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function show(Destination $destination)
    {
         // Biasanya tidak perlu view 'show' di admin, tapi bisa dibuat jika perlu detail lengkap
         return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination)
    {
        $availableFacilities = Facility::whereDoesntHave('destinations', function ($query) use ($destination) {
            $query->where('destination_id', $destination->id);
        })->orderBy('name')->get();
    
        // Ambil fasilitas yang SUDAH terhubung (gunakan relasi yang mengambil semua pivot)
        $linkedFacilities = $destination->allFacilitiesPivot()->get(); 

        return view('admin.destinations.edit', compact('destination', 'availableFacilities', 'linkedFacilities'));
    }

    public function update(Request $request, Destination $destination)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'base_ticket_price' => 'required|numeric|min:0',
            'visitor_quota' => 'nullable|integer|min:0',
            'main_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle File Upload (Update)
        if ($request->hasFile('main_photo')) {
            // 1. Hapus foto lama jika ada
            if ($destination->main_photo && Storage::disk('public')->exists($destination->main_photo)) {
                Storage::disk('public')->delete($destination->main_photo);
            }
            // 2. Upload foto baru
            $path = $request->file('main_photo')->store('destinations', 'public');
            $validated['main_photo'] = $path;
        } else {
            // Jika tidak ada file baru, jangan ubah kolom main_photo
            unset($validated['main_photo']);
        }

         // Update slug jika nama berubah (jika tidak otomatis di model)
        // if ($destination->name !== $validated['name']) {
        //     $validated['slug'] = Str::slug($validated['name']);
        //     // Handle keunikan slug
        // }

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
                         ->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination)
    {
        // Hapus foto terkait sebelum menghapus record
        if ($destination->main_photo && Storage::disk('public')->exists($destination->main_photo)) {
            Storage::disk('public')->delete($destination->main_photo);
        }

        // Hapus juga fasilitas terkait? Atau set null? Tergantung aturan bisnis
        // $destination->facilities()->delete(); // Contoh hapus fasilitas terkait

        $destination->delete();

        return redirect()->route('admin.destinations.index')
                         ->with('success', 'Destinasi berhasil dihapus.');
    }
}