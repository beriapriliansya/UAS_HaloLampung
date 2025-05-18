<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk unique rule di pivot

class DestinationFacilityController extends Controller
{
    // Menambahkan fasilitas ke destinasi
    public function attach(Request $request, Destination $destination)
    {
        // Validasi dengan error bag terpisah agar tidak bentrok dengan form utama destinasi
        $validated = $request->validateWithBag('attachFacility', [
            'facility_id' => [
                'required',
                'exists:facilities,id',
                // Pastikan belum terhubung ke destinasi ini
                Rule::unique('destination_facility')->where(function ($query) use ($destination) {
                    return $query->where('destination_id', $destination->id);
                }),
            ],
            'price' => 'required|numeric|min:0',
            'quota' => 'nullable|integer|min:0',
        ], [
            // Custom error messages
            'facility_id.unique' => 'Fasilitas ini sudah ditambahkan ke destinasi.',
        ]);

        // Lakukan attach ke tabel pivot
        $destination->facilities()->attach($validated['facility_id'], [
            'price' => $validated['price'],
            'quota' => $validated['quota'],
            'is_available' => true, // Default true saat ditambahkan
            'created_at' => now(), // Manual timestamp jika perlu
            'updated_at' => now(), // Manual timestamp jika perlu
        ]);

        return redirect()->route('admin.destinations.edit', $destination)
                         ->with('success', 'Fasilitas berhasil ditambahkan ke destinasi.');
    }

    // Mengupdate data pivot (harga, kuota, status)
    public function update(Request $request, Destination $destination, Facility $facility)
    {
        // Validasi (bisa pakai error bag juga jika mau, atau biarkan default)
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quota' => 'nullable|integer|min:0',
            'is_available' => 'required|boolean',
        ]);

        // Update data di tabel pivot
        $destination->facilities()->updateExistingPivot($facility->id, [
            'price' => $validated['price'],
            'quota' => $validated['quota'],
            'is_available' => $validated['is_available'],
            'updated_at' => now(), // Manual timestamp jika perlu
        ]);

         return redirect()->route('admin.destinations.edit', $destination)
                         ->with('success', 'Fasilitas '. $facility->name .' berhasil diperbarui.');
    }

    // Melepas hubungan fasilitas dari destinasi
    public function detach(Destination $destination, Facility $facility)
    {
        $destination->facilities()->detach($facility->id);

        return redirect()->route('admin.destinations.edit', $destination)
                         ->with('success', 'Fasilitas '. $facility->name .' berhasil dihapus dari destinasi.');
    }
}