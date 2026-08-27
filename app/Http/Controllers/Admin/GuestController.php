<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = Guest::orderBy('created_at', 'desc');

        if (!$user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        $guests = $query->get();
        $info = \App\Models\Info::where('user_id', $user->id)->first();
        return view('admin.guests.index', compact('guests', 'info'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'no_hp' => 'nullable|max:20',
            'keterangan' => 'nullable|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status_kirim'] = false;

        Guest::create($validated);

        return redirect()->route('guests.index')->with('success', 'Tamu berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $guest->user_id && $guest->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nama' => 'required|max:255',
            'no_hp' => 'nullable|max:20',
            'keterangan' => 'nullable|max:255',
        ]);

        $guest->update($validated);

        return redirect()->route('guests.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $guest->user_id && $guest->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $guest->delete();

        return redirect()->route('guests.index')->with('success', 'Tamu berhasil dihapus.');
    }

    /**
     * Toggle status pengiriman WhatsApp (Sudah / Belum Dikirim).
     */
    public function toggleSent(Request $request, Guest $guest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $guest->user_id && $guest->user_id !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
        }

        if ($request->has('status')) {
            $guest->status_kirim = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);
        } else {
            $guest->status_kirim = !$guest->status_kirim;
        }
        $guest->save();

        return response()->json([
            'status' => 'success',
            'status_kirim' => (bool) $guest->status_kirim,
            'message' => $guest->status_kirim ? 'Status diperbarui: Sudah Dikirim' : 'Status diperbarui: Belum Dikirim'
        ]);
    }

    /**
     * Export data tamu ke format CSV.
     */
    public function exportCsv()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = Guest::orderBy('created_at', 'desc');

        if (!$user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        $guests = $query->get();
        $fileName = 'Daftar_Tamu_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($guests) {
            $file = fopen('php://output', 'w');
            // Write BOM for Excel UTF-8 support
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Nama Tamu', 'No WhatsApp', 'Keterangan', 'Status Pengiriman Undangan', 'Tanggal Dibuat']);

            foreach ($guests as $index => $guest) {
                fputcsv($file, [
                    $index + 1,
                    $guest->nama,
                    $guest->no_hp ?: '-',
                    $guest->keterangan ?: '-',
                    $guest->status_kirim ? 'Sudah Dikirim' : 'Belum Dikirim',
                    $guest->created_at ? $guest->created_at->format('Y-m-d H:i') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Unduh template file Excel / CSV untuk impor data tamu massal.
     */
    public function downloadTemplate()
    {
        $fileName = 'Template_Import_Tamu_Undangan.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // Write BOM for Excel UTF-8 support
            fputs($file, "\xEF\xBB\xBF");
            // Header kolom
            fputcsv($file, ['Nama Tamu', 'No WhatsApp', 'Keterangan']);
            // Contoh baris sampel
            fputcsv($file, ['Budi Santoso', '6281234567890', 'Teman SMA']);
            fputcsv($file, ['Siti Aminah', '085712345678', 'Keluarga']);
            fputcsv($file, ['Ahmad Ridwan', '', 'Rekan Kerja']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Impor data tamu massal dari file Excel / CSV.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ], [
            'file.required' => 'Pilihlah file Excel / CSV terlebih dahulu.',
            'file.max' => 'Ukuran file tidak boleh melebihi 5MB.',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, ['csv', 'txt'])) {
            return redirect()->back()->with('error', 'Format file harus berupa CSV (.csv atau .txt).');
        }

        $filePath = $file->getRealPath();
        $handle = fopen($filePath, 'r');

        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membaca file yang diunggah.');
        }

        // Detect delimiter (Comma vs Semicolon) from the first line
        $firstLine = fgets($handle);
        rewind($handle);
        
        // Remove UTF-8 BOM if present
        $firstLineClean = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $firstLine);
        $delimiter = (strpos($firstLineClean, ';') !== false && strpos($firstLineClean, ',') === false) ? ';' : ',';

        $importedCount = 0;
        $skippedCount = 0;
        $isFirstRow = true;
        $userId = Auth::id();

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if (empty($row) || (count($row) === 1 && trim($row[0]) === '')) {
                continue; // Skip empty lines
            }

            // Detect and skip header line
            if ($isFirstRow) {
                $isFirstRow = false;
                $firstCol = strtolower(trim(preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $row[0] ?? '')));
                if (in_array($firstCol, ['nama', 'nama tamu', 'nama_tamu', 'no', 'name'])) {
                    continue;
                }
            }

            $nama = isset($row[0]) ? trim(preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $row[0])) : '';
            $noHp = isset($row[1]) ? trim($row[1]) : '';
            $keterangan = isset($row[2]) ? trim($row[2]) : '';

            // Abaikan jika nama kosong
            if ($nama === '') {
                $skippedCount++;
                continue;
            }

            Guest::create([
                'user_id' => $userId,
                'nama' => $nama,
                'no_hp' => $noHp ?: null,
                'keterangan' => $keterangan ?: null,
                'status_kirim' => false,
            ]);

            $importedCount++;
        }

        fclose($handle);

        if ($importedCount === 0) {
            return redirect()->route('guests.index')->with('error', 'Tidak ada data tamu yang valid ditemukan dalam file.');
        }

        $msg = "Berhasil mengimpor {$importedCount} data tamu dari Excel/CSV.";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} baris kosong dilewati)";
        }

        return redirect()->route('guests.index')->with('success', $msg);
    }
}
