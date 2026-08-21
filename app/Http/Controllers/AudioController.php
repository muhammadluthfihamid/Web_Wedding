<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    /**
     * Halaman kelola musik / audio undangan.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cari info user, hubungkan record lama jika null, atau buat baru jika belum ada
        $info = Info::where('user_id', $user->id)->first();
        if (!$info) {
            $info = Info::whereNull('user_id')->first();
            if ($info) {
                $info->update(['user_id' => $user->id]);
            }
        }
        if (!$info) {
            $info = Info::create([
                'user_id'              => $user->id,
                'nama_pengantin_pria'  => 'Pengantin Pria',
                'nama_pengantin_istri' => 'Pengantin Wanita',
                'tanggal_pernikahan'   => now()->toDateString(),
                'mulai_akad'           => '08:00',
                'selesai_akad'         => '10:00',
                'mulai_resepsi'        => '11:00',
                'alamat'               => 'Alamat Acara Pernikahan',
                'deskripsi'            => 'Acara Pernikahan',
            ]);
        }

        // Ambil daftar preset musik di public/assets/audio/
        $presetPath = public_path('assets/audio');
        $presets = [];
        $categoryMap = [
            'Bi Saraha' => 'Arabic Romantic',
            'Canon in D (Pachelbel)' => 'Classical Piano',
            'save-and-sound' => 'Soft Acoustic',
            'Sweet Acoustic Guitar' => 'Garden Acoustic',
        ];

        if (File::exists($presetPath)) {
            $files = File::files($presetPath);
            foreach ($files as $file) {
                if (in_array(strtolower($file->getExtension()), ['mp3', 'wav', 'ogg', 'm4a'])) {
                    $filename = $file->getFilename();
                    $name = pathinfo($filename, PATHINFO_FILENAME);
                    $category = $categoryMap[$name] ?? 'Musik Undangan';

                    $presets[] = [
                        'name'     => $name,
                        'filename' => $filename,
                        'path'     => 'assets/audio/' . $filename,
                        'category' => $category,
                    ];
                }
            }
        }

        return view('admin.audio.index', compact('info', 'presets'));
    }

    /**
     * Simpan / perbarui musik undangan.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $info = Info::where('user_id', $user->id)->first();

        if (!$info) {
            $info = Info::create([
                'user_id'              => $user->id,
                'nama_pengantin_pria'  => 'Pengantin Pria',
                'nama_pengantin_istri' => 'Pengantin Wanita',
                'tanggal_pernikahan'   => now()->toDateString(),
                'mulai_akad'           => '08:00',
                'selesai_akad'         => '10:00',
                'mulai_resepsi'        => '11:00',
                'alamat'               => 'Alamat Acara Pernikahan',
                'deskripsi'            => 'Acara Pernikahan',
            ]);
        }

        $request->validate([
            'audio_option'     => 'required|in:preset,upload,url',
            'preset_audio'     => 'nullable|string',
            'audio_file'       => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:15360',
            'custom_audio_url' => 'nullable|url',
        ]);

        $musikUrl = $info->musik_url;

        if ($request->audio_option === 'upload' && $request->hasFile('audio_file')) {
            // Hapus audio upload lama jika ada
            if ($info->musik_url && str_starts_with($info->musik_url, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $info->musik_url));
            }

            $path = $request->file('audio_file')->store('audio', 'public');
            $musikUrl = 'storage/' . $path;
        } elseif ($request->audio_option === 'preset' && $request->filled('preset_audio')) {
            $musikUrl = $request->preset_audio;
        } elseif ($request->audio_option === 'url' && $request->filled('custom_audio_url')) {
            $musikUrl = $request->custom_audio_url;
        }

        $info->update([
            'musik_url'       => $musikUrl,
            'is_audio_active' => $request->has('is_audio_active'),
        ]);

        return redirect()->route('audio.index')->with('success', 'Musik latar undangan berhasil diperbarui!');
    }

    /**
     * Tambah preset audio baru ke public/assets/audio/ (Khusus Super Admin).
     */
    public function storePreset(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Akses khusus Super Admin.');
        }

        $request->validate([
            'preset_name' => 'required|string|max:255',
            'preset_file' => 'required|file|mimes:mp3,wav,ogg,m4a|max:15360',
        ]);

        $file = $request->file('preset_file');
        $extension = $file->getClientOriginalExtension();
        $cleanTitle = trim(preg_replace('/[^\w\s\-]/', '', $request->preset_name));
        if (empty($cleanTitle)) {
            $cleanTitle = 'Preset_' . time();
        }
        $filename = $cleanTitle . '.' . $extension;

        $targetDir = public_path('assets/audio');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $file->move($targetDir, $filename);

        return redirect()->route('audio.index')->with('success', 'Preset musik "' . $cleanTitle . '" berhasil ditambahkan ke playlist sistem!');
    }

    /**
     * Hapus preset audio dari public/assets/audio/ (Khusus Super Admin).
     */
    public function destroyPreset(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Akses khusus Super Admin.');
        }

        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = basename($request->filename);
        $filePath = public_path('assets/audio/' . $filename);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->route('audio.index')->with('success', 'Preset musik "' . $filename . '" berhasil dihapus dari playlist sistem!');
        }

        return redirect()->route('audio.index')->with('success', 'File preset telah dihapus.');
    }
}
