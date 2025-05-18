<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Untuk validasi
use Illuminate\Support\Facades\Mail; // Jika kirim email konfirmasi
use App\Mail\SubscriptionConfirmationMail; // Mailable yg akan kita buat
use Illuminate\Support\Facades\Log;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Menyimpan email subscriber baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:subscribers,email', // Pastikan email unik di tabel subscribers
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar untuk berlangganan.',
        ]);

        if ($validator->fails()) {
            // Jika request dari AJAX, kembalikan JSON
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            // Jika request biasa, redirect dengan error
            return back()->withErrors($validator)->withInput();
        }

        // Jika validasi berhasil
        try {
            $subscriber = Subscriber::create([
                'email' => $request->email,
                'verified_at' => null,
                'is_subscribed' => false,
            ]);

            Mail::to($subscriber->email)->send(new SubscriptionConfirmationMail($subscriber));
            Log::info("Email konfirmasi langganan dikirim ke {$subscriber->email}");

            $successMessage = 'Terima kasih! Anda berhasil berlangganan newsletter kami.';
            return back()->with('success_newsletter', $successMessage);
        } catch (\Exception $e) {
            Log::error("Gagal subscribe: " . $e->getMessage());

            return back()->with('error_newsletter', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.');



            if ($request->ajax()) {
                return response()->json(['message' => $successMessage], 200);
            }
            return back()->with('success_newsletter', $successMessage);
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan subscriber: {$request->email}. Error: " . $e->getMessage());
            $errorMessage = 'Terjadi kesalahan. Silakan coba lagi nanti.';
            if ($request->ajax()) {
                return response()->json(['message' => $errorMessage], 500);
            }
            return back()->with('error_newsletter', $errorMessage);
        }
    }

    /**
     * Mengkonfirmasi langganan (jika menggunakan double opt-in).
     */
    public function confirm(Request $request, $token)
    {
        $subscriber = Subscriber::where('token', $token)->whereNull('verified_at')->first();

        if ($subscriber) {
            $subscriber->verified_at = now();
            $subscriber->is_subscribed = true; // Pastikan status subscribed
            $subscriber->save();

            // Redirect ke halaman terima kasih atau home dengan pesan sukses
            return redirect()->route('home')->with('success', 'Langganan Anda telah berhasil dikonfirmasi! Terima kasih.');
        }

        // Token tidak valid atau sudah dikonfirmasi
        return redirect()->route('home')->with('error', 'Link konfirmasi tidak valid atau sudah digunakan.');
    }

    /**
     * Berhenti berlangganan.
     */
    public function unsubscribe(Request $request, $token) // Atau bisa juga by email jika tidak pakai token
    {
        $subscriber = Subscriber::where('token', $token)->where('is_subscribed', true)->first();

        if ($subscriber) {
            $subscriber->is_subscribed = false;
            $subscriber->save();
            return redirect()->route('home')->with('success', 'Anda telah berhasil berhenti berlangganan.');
        }
        return redirect()->route('home')->with('error', 'Link berhenti langganan tidak valid.');
    }
}
