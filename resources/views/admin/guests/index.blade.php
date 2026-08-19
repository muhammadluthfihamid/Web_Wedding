use Illuminate\Support\Facades\Auth;
@extends('admin.layouts.admin')

@section('main-content')
<!-- Page Heading -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Tamu (List Undangan)</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola daftar penerima undangan dan buat link undangan kustom untuk dibagikan</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
        <a href="{{ route('guests.exportCsv') }}"
            class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-slate-300 rounded-lg shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            <i class="fas fa-file-excel text-emerald-600"></i> Export Excel/CSV
        </a>
        <button onclick="openImportModal()"
            class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-emerald-600 rounded-lg shadow-sm text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors" role="button">
            <i class="fas fa-file-import text-emerald-600"></i> Impor Excel
        </button>
        <button onclick="openAddModal()"
            class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" role="button">
            <i class="fas fa-plus"></i> Tambah Tamu
        </button>
    </div>
</div>

<!-- Stats summary cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
            <i class="fas fa-users text-2xl"></i>
        </div>
        <div>
            <span class="block text-sm text-slate-500 font-medium">Total Tamu</span>
            <span class="text-2xl font-bold text-slate-950">{{ $guests->count() }}</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
            <i class="fab fa-whatsapp text-2xl"></i>
        </div>
        <div>
            <span class="block text-sm text-slate-500 font-medium">Memiliki No. WA</span>
            <span class="text-2xl font-bold text-slate-950">{{ $guests->whereNotNull('no_hp')->where('no_hp', '!=', '')->count() }}</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
            <i class="fas fa-paper-plane text-2xl"></i>
        </div>
        <div>
            <span class="block text-sm text-slate-500 font-medium">Sudah Dikirim WA</span>
            <span class="text-2xl font-bold text-slate-950">{{ $guests->where('status_kirim', true)->count() }}</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
            <i class="fas fa-link text-2xl"></i>
        </div>
        <div>
            <span class="block text-sm text-slate-500 font-medium">Kategori Keterangan</span>
            <span class="text-2xl font-bold text-slate-950">{{ $guests->pluck('keterangan')->unique()->filter()->count() }}</span>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50/50">
                <tr>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-[5%]">No.</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Tamu</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. WhatsApp</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Kirim</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Link Undangan</th>
                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-[25%]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @if ($guests->isEmpty())
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="text-slate-400 text-sm flex flex-col items-center justify-center gap-2">
                            <i class="fas fa-users text-4xl text-slate-300 mb-2"></i>
                            <span>Belum ada data tamu. Klik "Tambah Tamu" untuk memulai.</span>
                        </div>
                    </td>
                </tr>
                @else
                @php
                $namaIstri = $info?->nama_pengantin_istri ?? 'Annisa';
                $namaPria = $info?->nama_pengantin_pria ?? 'M luthfi';
                $coupleName = $namaIstri . ' & ' . $namaPria;
                @endphp
                @foreach ($guests as $guest)
                @php
                $baseUrl = url('/undangan/' . Auth::user()->getOrGenerateSlug());
                $weddingUrl = $baseUrl . '?to=' . rawurlencode($guest->nama);
                $waMessage = "_Assalamualaikum Warahmatullahi Wabarakatuh_\n\n"
                . "Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i *" . $guest->nama . "* untuk menghadiri acara kami.\n\n"
                . "*Berikut link undangan kami*, untuk info lengkap dari acara bisa kunjungi :\n\n"
                . $weddingUrl . "\n\n"
                . "Merupakan suatu kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu.\n\n"
                . "*Mohon maaf perihal undangan hanya di bagikan melalui pesan ini.*\n\n"
                . "Dan agar selalu menjaga kesehatan bersama serta datang pada waktu yang telah ditentukan.\n\n"
                . "Terima kasih banyak atas perhatiannya.\n\n"
                . "_Wassalamualaikum Warahmatullahi Wabarakatuh_";

                $cleanPhone = preg_replace('/[^0-9]/', '', $guest->no_hp ?? '');
                if ($cleanPhone) {
                if (strpos($cleanPhone, '0') === 0) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
                } elseif (strpos($cleanPhone, '8') === 0) {
                $cleanPhone = '62' . $cleanPhone;
                }
                }

                $waLink = $cleanPhone
                ? "https://api.whatsapp.com/send?phone=" . $cleanPhone . "&text=" . rawurlencode($waMessage)
                : "https://api.whatsapp.com/send?text=" . rawurlencode($waMessage);
                @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">{{ $guest->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        @if ($guest->no_hp)
                        <span class="inline-flex items-center gap-1 font-mono bg-slate-50 text-slate-700 px-2.5 py-1 rounded-md text-xs border border-slate-100">
                            <i class="fab fa-whatsapp text-emerald-500"></i> {{ $guest->no_hp }}
                        </span>
                        @else
                        <span class="text-xs text-slate-400 font-normal italic">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button type="button" data-guest-id="{{ $guest->id }}" onclick="toggleSentStatus(this.dataset.guestId, this)"
                            class="status-btn inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all cursor-pointer {{ $guest->status_kirim ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}"
                            title="Klik untuk mengubah status pengiriman WA">
                            <i class="fas {{ $guest->status_kirim ? 'fa-check-circle text-emerald-600' : 'fa-clock text-slate-400' }}"></i>
                            <span>{{ $guest->status_kirim ? 'Sudah Dikirim' : 'Belum Dikirim' }}</span>
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ $guest->keterangan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-800 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $weddingUrl }}" class="bg-slate-50 border border-slate-200 text-slate-600 px-2 py-1 rounded text-xs w-48 font-mono select-all focus:outline-none">
                            <button onclick="copyToClipboard('{{ $weddingUrl }}')" class="p-1 hover:bg-slate-100 text-slate-500 hover:text-indigo-600 rounded transition-colors" title="Salin Link">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ $waLink }}" target="_blank" data-guest-id="{{ $guest->id }}" onclick="markAsSentOnWaClick(this.dataset.guestId)"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold transition-colors">
                                <i class="fab fa-whatsapp"></i> Kirim WA
                            </a>
                            <button onclick="openShareModal(this)"
                                data-nama="{{ $guest->nama }}"
                                data-nohp="{{ $guest->no_hp }}"
                                data-link="{{ $weddingUrl }}"
                                data-message="{{ $waMessage }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold transition-colors">
                                <i class="fas fa-share-alt"></i> Share
                            </button>
                            <button
                                class="btn-edit inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold transition-colors"
                                data-id="{{ $guest->id }}"
                                data-nama="{{ addslashes($guest->nama) }}"
                                data-no_hp="{{ $guest->no_hp }}"
                                data-keterangan="{{ addslashes($guest->keterangan) }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-delete inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors"
                                data-id="{{ $guest->id }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        <form id="delete-form-{{ $guest->id }}" action="{{ route('guests.destroy', $guest) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- ADD GUEST MODAL -->
<div id="add-modal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 shadow-2xl border border-slate-100 w-full max-w-md mx-4 transform transition-all duration-300">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-slate-900"><i class="fas fa-user-plus text-indigo-600 mr-1.5"></i>Tambah Tamu Undangan</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('guests.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Tamu <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: Teh Nisa / Bapak Budi" class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">No. WhatsApp <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="no_hp" placeholder="Contoh: 6281234567890" class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                    <span class="text-[10px] text-slate-400 mt-1 block">Gunakan kode negara (62...) untuk mempermudah kirim otomatis WhatsApp</span>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Keterangan / Kelompok <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="keterangan" placeholder="Contoh: Teman SD / Rekan Kerja" class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT GUEST MODAL -->
<div id="edit-modal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 shadow-2xl border border-slate-100 w-full max-w-md mx-4 transform transition-all duration-300">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-slate-900"><i class="fas fa-user-edit text-amber-600 mr-1.5"></i>Edit Tamu Undangan</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Tamu <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-nama" name="nama" required placeholder="Nama Tamu" class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">No. WhatsApp <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" id="edit-nohp" name="no_hp" placeholder="Contoh: 6281234567890" class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Keterangan / Kelompok <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" id="edit-keterangan" name="keterangan" placeholder="Contoh: Teman SD" class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-lg shadow-sm transition-colors">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- SHARE MODAL -->
<div id="share-modal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 shadow-2xl border border-slate-100 w-full max-w-lg mx-4 transform transition-all duration-300">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-slate-900"><i class="fas fa-paper-plane text-indigo-600 mr-1.5"></i>Bagikan Undangan</h3>
            <button onclick="closeShareModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Nama Penerima</label>
                <div id="share-recipient-name" class="text-slate-800 font-bold text-base bg-slate-50 px-3.5 py-2.5 rounded-lg border border-slate-100">Nama Tamu</div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Link Undangan</label>
                <div class="flex items-center gap-2">
                    <input type="text" id="share-link-input" readonly class="w-full bg-slate-50 border border-slate-200 text-slate-600 px-3.5 py-2 rounded-lg text-sm font-mono select-all focus:outline-none">
                    <button onclick="copyShareLink()" class="inline-flex items-center justify-center p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm transition-colors" title="Salin Link">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Preview Pesan WhatsApp</label>
                <textarea id="share-message-textarea" rows="6" readonly class="w-full bg-slate-50 border border-slate-200 text-slate-700 p-3 rounded-lg text-xs font-mono focus:outline-none select-all"></textarea>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 border-t border-slate-100 pt-4">
            <button type="button" onclick="closeShareModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors order-last sm:order-first">Tutup</button>
            <button type="button" onclick="copyShareMessage()" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                <i class="fas fa-copy"></i> Salin Pesan
            </button>
            <a href="#" id="share-wa-btn" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-colors">
                <i class="fab fa-whatsapp"></i> Kirim WhatsApp
            </a>
        </div>
    </div>
</div>

<!-- IMPORT GUEST MODAL -->
<div id="import-modal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 shadow-2xl border border-slate-100 w-full max-w-lg mx-4 transform transition-all duration-300">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-slate-900"><i class="fas fa-file-excel text-emerald-600 mr-1.5"></i>Impor Data Tamu dari Excel / CSV</h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Instructions & Template -->
        <div class="bg-emerald-50/70 border border-emerald-100 rounded-xl p-4 mb-4 text-xs text-slate-600 leading-relaxed space-y-2">
            <div class="font-bold text-emerald-800 flex items-center gap-1.5 text-sm mb-1">
                <i class="fas fa-info-circle text-emerald-600"></i> Petunjuk Impor Data Excel / CSV
            </div>
            <p>1. Unduh template file Excel/CSV di bawah ini agar struktur kolom sesuai.</p>
            <p>2. Buka file di Microsoft Excel, isi kolom <strong>Nama Tamu</strong>, <strong>No WhatsApp</strong> (misal 628123...), dan <strong>Keterangan</strong>.</p>
            <p>3. Simpan file lalu unggah pada form di bawah ini.</p>
            <div class="pt-2">
                <a href="{{ route('guests.downloadTemplate') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-xs transition-colors shadow-sm">
                    <i class="fas fa-download"></i> Unduh Template Excel (.csv)
                </a>
            </div>
        </div>

        <form action="{{ route('guests.importCsv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Pilih File Excel / CSV <span class="text-rose-500">*</span></label>
                    <input type="file" name="file" accept=".csv, .txt" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-200 rounded-lg cursor-pointer">
                    <span class="text-[10px] text-slate-400 mt-1 block">Format didukung: CSV (.csv / .txt). Maksimal 5MB.</span>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeImportModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-upload"></i> Unggah & Impor Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    function openImportModal() {
        document.getElementById('import-modal').classList.remove('hidden');
        document.getElementById('import-modal').classList.add('flex');
    }

    function closeImportModal() {
        document.getElementById('import-modal').classList.remove('flex');
        document.getElementById('import-modal').classList.add('hidden');
    }

    function toggleSentStatus(guestId, btnElement) {
        fetch("{{ url('/admin/guests') }}/" + guestId + "/toggle-sent", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.status_kirim) {
                        btnElement.className = "status-btn inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all cursor-pointer bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100";
                        btnElement.innerHTML = '<i class="fas fa-check-circle text-emerald-600"></i><span>Sudah Dikirim</span>';
                    } else {
                        btnElement.className = "status-btn inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all cursor-pointer bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200";
                        btnElement.innerHTML = '<i class="fas fa-clock text-slate-400"></i><span>Belum Dikirim</span>';
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(err => {
                console.error("Gagal memperbarui status kirim: ", err);
            });
    }

    function markAsSentOnWaClick(guestId) {
        // auto toggle status to true when clicking Kirim WA
        const btn = document.querySelector(`button.status-btn[data-guest-id="${guestId}"]`);
        if (btn && btn.innerText.includes('Belum Dikirim')) {
            toggleSentStatus(guestId, btn);
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Link berhasil disalin!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.dataset.id;
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data tamu ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-edit').forEach(function(btn) {
            btn.addEventListener('click', function() {
                openEditModal(
                    this.dataset.id,
                    this.dataset.nama,
                    this.dataset.no_hp,
                    this.dataset.keterangan
                );
            });
        });
    });

    function openAddModal() {
        document.getElementById('add-modal').classList.remove('hidden');
        document.getElementById('add-modal').classList.add('flex');
    }

    function closeAddModal() {
        document.getElementById('add-modal').classList.remove('flex');
        document.getElementById('add-modal').classList.add('hidden');
    }

    function openEditModal(id, nama, nohp, keterangan) {
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-nohp').value = nohp;
        document.getElementById('edit-keterangan').value = keterangan;
        document.getElementById('edit-form').action = "{{ url('/admin/guests') }}/" + id;

        document.getElementById('edit-modal').classList.remove('hidden');
        document.getElementById('edit-modal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.remove('flex');
        document.getElementById('edit-modal').classList.add('hidden');
    }

    function formatWaPhone(phone) {
        if (!phone) return '';
        let clean = phone.replace(/[^0-9]/g, '');
        if (clean.startsWith('0')) {
            clean = '62' + clean.substring(1);
        } else if (clean.startsWith('8')) {
            clean = '62' + clean;
        }
        return clean;
    }

    function openShareModal(button) {
        const nama = button.getAttribute('data-nama');
        const nohp = button.getAttribute('data-nohp');
        const link = button.getAttribute('data-link');
        const message = button.getAttribute('data-message');

        document.getElementById('share-recipient-name').innerText = nama;
        document.getElementById('share-link-input').value = link;
        document.getElementById('share-message-textarea').value = message;

        const cleanPhone = formatWaPhone(nohp);
        if (cleanPhone) {
            document.getElementById('share-wa-btn').href = "https://api.whatsapp.com/send?phone=" + cleanPhone + "&text=" + encodeURIComponent(message);
        } else {
            document.getElementById('share-wa-btn').href = "https://api.whatsapp.com/send?text=" + encodeURIComponent(message);
        }

        document.getElementById('share-modal').classList.remove('hidden');
        document.getElementById('share-modal').classList.add('flex');
    }

    function closeShareModal() {
        document.getElementById('share-modal').classList.remove('flex');
        document.getElementById('share-modal').classList.add('hidden');
    }

    function copyShareLink() {
        const linkInput = document.getElementById('share-link-input');
        copyToClipboard(linkInput.value);
    }

    function copyShareMessage() {
        const messageTextarea = document.getElementById('share-message-textarea');
        navigator.clipboard.writeText(messageTextarea.value).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Pesan berhasil disalin!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    }
</script>
@endsection