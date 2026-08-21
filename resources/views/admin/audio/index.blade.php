@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Musik / Audio Undangan</h1>
            <p class="text-sm text-slate-500 mt-1">Atur musik latar belakang (backsound) yang diputar di website undangan Anda</p>
        </div>
        @if($info->user)
            <a href="{{ route('undangan.show', $info->user->getOrGenerateSlug()) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-sm font-semibold rounded-xl transition-colors">
                <i class="fas fa-play text-emerald-600"></i> Tes Dengar di Undangan
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-emerald-500 text-lg mr-3"></i>
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm" role="alert">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-rose-500 text-lg mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-semibold text-rose-800">Gagal menyimpan musik:</h3>
                    <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-music text-indigo-600"></i> Pengaturan Musik Latar
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $info->is_audio_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                        {{ $info->is_audio_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <div class="p-6">
                    <form action="{{ route('audio.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Switch Aktifkan Musik -->
                        <div class="p-4 bg-indigo-50/60 rounded-xl border border-indigo-100 flex items-center justify-between">
                            <div>
                                <label for="is_audio_active" class="text-sm font-bold text-indigo-950 cursor-pointer">Putar Musik Otomatis</label>
                                <p class="text-xs text-indigo-700 mt-0.5">Aktifkan musik background yang berputar otomatis saat tamu membuka undangan</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_audio_active" name="is_audio_active" value="1" class="sr-only peer" {{ old('is_audio_active', $info->is_audio_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <!-- Opsi Sumber Musik -->
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-slate-800">Pilih Metode/Sumber Musik:</label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Option 1: Preset -->
                                <label class="relative flex flex-col p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-indigo-400 transition-all text-center group option-card">
                                    <input type="radio" name="audio_option" value="preset" class="sr-only peer" 
                                           {{ old('audio_option', (!$info->musik_url || str_starts_with($info->musik_url, 'assets/')) ? 'preset' : '') === 'preset' ? 'checked' : '' }}>
                                    <div class="peer-checked:text-indigo-600 peer-checked:border-indigo-600">
                                        <i class="fas fa-list-music text-2xl text-slate-400 group-hover:text-indigo-500 mb-2 transition-colors"></i>
                                        <div class="text-xs font-bold text-slate-800">Pilih Preset</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">Musik Pilihan Sistem</div>
                                    </div>
                                </label>

                                <!-- Option 2: Upload File -->
                                <label class="relative flex flex-col p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-indigo-400 transition-all text-center group option-card">
                                    <input type="radio" name="audio_option" value="upload" class="sr-only peer"
                                           {{ old('audio_option', (str_starts_with($info->musik_url, 'storage/')) ? 'upload' : '') === 'upload' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 group-hover:text-indigo-500 mb-2 transition-colors"></i>
                                        <div class="text-xs font-bold text-slate-800">Upload MP3</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">File Sendiri (Max 15MB)</div>
                                    </div>
                                </label>

                                <!-- Option 3: External URL -->
                                <label class="relative flex flex-col p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-indigo-400 transition-all text-center group option-card">
                                    <input type="radio" name="audio_option" value="url" class="sr-only peer"
                                           {{ old('audio_option', (str_starts_with($info->musik_url, 'http')) ? 'url' : '') === 'url' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-link text-2xl text-slate-400 group-hover:text-indigo-500 mb-2 transition-colors"></i>
                                        <div class="text-xs font-bold text-slate-800">Link URL MP3</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">Link Direct Internet</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Content 1: Preset Selection -->
                        <div id="section-preset" class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-semibold text-slate-800">Pilih dari Playlist Lagu Pernikahan:</label>
                                <span class="text-xs font-medium text-slate-500 bg-white px-2.5 py-1 rounded-md border border-slate-200">{{ count($presets) }} Pilihan Lagu</span>
                            </div>

                            @if(auth()->user() && auth()->user()->isSuperAdmin())
                                <!-- Super Admin Add Preset Box -->
                                <div class="p-4 bg-indigo-50/90 border border-indigo-200 rounded-xl space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-plus-circle text-indigo-600"></i> Tambah Preset Ke Playlist Sistem (Super Admin)
                                        </h4>
                                        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded">Tampil untuk Semua User</span>
                                    </div>
                                    <form action="{{ route('audio.storePreset') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                        @csrf
                                        <div class="sm:col-span-5">
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Judul / Nama Lagu:</label>
                                            <input type="text" name="preset_name" required placeholder="Contoh: Janji Suci Acoustic" class="block w-full px-3 py-1.5 border border-slate-300 rounded-lg text-xs bg-white text-slate-900 focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <div class="sm:col-span-4">
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">File Audio (Max 15MB):</label>
                                            <input type="file" name="preset_file" accept="audio/mp3,audio/wav,audio/ogg,audio/m4a" required class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer border border-slate-300 rounded-lg bg-white">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <button type="submit" class="w-full px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-sm transition-colors flex items-center justify-center gap-1.5">
                                                <i class="fas fa-plus"></i> Tambah Preset
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if(count($presets) > 0)
                                <div class="space-y-2 max-h-[420px] overflow-y-auto pr-1">
                                    @foreach($presets as $preset)
                                        <label class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-200 hover:border-indigo-300 cursor-pointer transition-all">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="preset_audio" value="{{ $preset['path'] }}" class="text-indigo-600 focus:ring-indigo-500"
                                                       {{ old('preset_audio', $info->musik_url ?? 'assets/audio/Bi Saraha.mp3') === $preset['path'] ? 'checked' : '' }}>
                                                <div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="text-sm font-semibold text-slate-800">{{ $preset['name'] }}</span>
                                                        @if(isset($preset['category']))
                                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $preset['category'] }}</span>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-slate-400 block truncate max-w-[200px] sm:max-w-[320px]">{{ $preset['filename'] }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1 flex-shrink-0">
                                                <button type="button" data-path="{{ str_starts_with($preset['path'], 'http') ? $preset['path'] : asset($preset['path']) }}" data-name="{{ $preset['name'] }}"
                                                        class="btn-play-preset p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors"
                                                        title="Putar Pratinjau">
                                                    <i class="fas fa-play"></i> Putar
                                                </button>

                                                @if(auth()->user() && auth()->user()->isSuperAdmin() && str_starts_with($preset['path'], 'assets/audio/'))
                                                    <button type="button" 
                                                            data-filename="{{ $preset['filename'] }}"
                                                            onclick="confirmDeletePreset(this.dataset.filename)"
                                                            class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors"
                                                            title="Hapus preset ini dari playlist sistem">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-500 italic">Belum ada preset lagu di server.</p>
                            @endif
                        </div>

                        @if(auth()->user() && auth()->user()->isSuperAdmin())
                            <form id="delete-preset-form" action="{{ route('audio.destroyPreset') }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="filename" id="delete-preset-filename">
                            </form>
                            <script>
                                function confirmDeletePreset(filename) {
                                    if (confirm('Apakah Anda yakin ingin menghapus preset "' + filename + '" dari playlist sistem?')) {
                                        document.getElementById('delete-preset-filename').value = filename;
                                        document.getElementById('delete-preset-form').submit();
                                    }
                                }
                            </script>
                        @endif

                        <!-- Content 2: Upload MP3 Custom -->
                        <div id="section-upload" class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200 hidden">
                            <label class="block text-sm font-semibold text-slate-800">Upload File MP3 Sendiri:</label>
                            <input type="file" name="audio_file" accept="audio/mp3,audio/wav,audio/ogg,audio/m4a" id="audio_file_input"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer border border-slate-200 rounded-lg bg-white">
                            <p class="text-xs text-slate-500">Format yang didukung: <strong>.mp3, .wav, .ogg, .m4a</strong> (Maksimal 15 MB).</p>
                        </div>

                        <!-- Content 3: External URL -->
                        <div id="section-url" class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200 hidden">
                            <label class="block text-sm font-semibold text-slate-800">Masukkan Direct Link URL File MP3:</label>
                            <input type="url" name="custom_audio_url" value="{{ old('custom_audio_url', str_starts_with($info->musik_url ?? '', 'http') ? $info->musik_url : '') }}"
                                   placeholder="https://domain.com/path/to/wedding-song.mp3"
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm bg-white">
                            <p class="text-xs text-slate-500">Pastikan URL adalah link langsung file audio (diakhiri .mp3) yang dapat diakses publik.</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="border-t border-slate-100 pt-5 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md transition-colors">
                                <i class="fas fa-save"></i> Simpan Perubahan Musik
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Player Column -->
        <div class="space-y-6">
            <!-- Live Audio Player -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                <h4 class="text-md font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-compact-disc text-rose-500 animate-spin" style="animation-duration: 4s;"></i> Pemutar Audio (Preview)
                </h4>
                
                <div class="p-4 bg-slate-900 text-white rounded-2xl space-y-3 text-center">
                    <div class="w-16 h-16 mx-auto bg-slate-800 rounded-full flex items-center justify-center text-indigo-400 border border-slate-700 shadow-inner">
                        <i class="fas fa-music text-2xl"></i>
                    </div>
                    <div>
                        <div id="player-title" class="text-sm font-bold truncate">
                            @if($info->musik_url)
                                {{ basename($info->musik_url) }}
                            @else
                                Bi Saraha.mp3 (Default)
                            @endif
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Musik Latar Undangan</div>
                    </div>

                    <audio id="preview-audio-player" controls class="w-full mt-2 rounded-lg" style="height: 36px;">
                        @php
                            $currentSrc = $info->musik_url ? (str_starts_with($info->musik_url, 'http') ? $info->musik_url : asset($info->musik_url)) : asset('assets/audio/Bi Saraha.mp3');
                        @endphp
                        <source id="preview-audio-source" src="{{ $currentSrc }}" type="audio/mp3">
                        Browser Anda tidak mendukung pemutar audio.
                    </audio>
                </div>

                <div class="text-xs text-slate-500 space-y-2 leading-relaxed">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-indigo-500 mt-0.5"></i>
                        <span>Musik akan otomatis diputar saat tamu mengeklik layar pertama (buka undangan).</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-volume-up text-emerald-500 mt-0.5"></i>
                        <span>Tamu dapat menjeda (pause) musik kapan saja menggunakan tombol floating disc di pojok bawah.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const optionRadios = document.querySelectorAll('input[name="audio_option"]');
        const sectionPreset = document.getElementById('section-preset');
        const sectionUpload = document.getElementById('section-upload');
        const sectionUrl = document.getElementById('section-url');

        function toggleSections() {
            const selected = document.querySelector('input[name="audio_option"]:checked')?.value || 'preset';
            
            sectionPreset.classList.add('hidden');
            sectionUpload.classList.add('hidden');
            sectionUrl.classList.add('hidden');

            if (selected === 'preset') {
                sectionPreset.classList.remove('hidden');
            } else if (selected === 'upload') {
                sectionUpload.classList.remove('hidden');
            } else if (selected === 'url') {
                sectionUrl.classList.remove('hidden');
            }
        }

        optionRadios.forEach(radio => {
            radio.addEventListener('change', toggleSections);
        });

        toggleSections();

        // Preset play buttons
        document.querySelectorAll('.btn-play-preset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                playPreview(this.dataset.path, this.dataset.name);
            });
        });

        // Audio preview player
        const audioFileInput = document.getElementById('audio_file_input');
        if (audioFileInput) {
            audioFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const objectUrl = URL.createObjectURL(file);
                    playPreview(objectUrl, file.name);
                }
            });
        }
    });

    function playPreview(src, title) {
        const player = document.getElementById('preview-audio-player');
        const source = document.getElementById('preview-audio-source');
        const titleEl = document.getElementById('player-title');

        source.src = src;
        player.load();
        player.play();
        if (title) {
            titleEl.textContent = title;
        }
    }
</script>
@endpush
