@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ Auth::user()->isKhitanan() ? 'Tambah Info Acara Khitan' : 'Tambah Info Pernikahan' }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ Auth::user()->isKhitanan() ? 'Buat informasi dasar untuk acara khitanan Anda' : 'Buat informasi dasar untuk acara pernikahan Anda' }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-rose-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-rose-800">Terdapat beberapa kesalahan:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-rose-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-4xl bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Form Informasi Acara</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('info.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">{{ Auth::user()->isKhitanan() ? 'Nama Ananda (Anak Khitan)' : 'Nama Pengantin Pria' }}<span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_pengantin_pria" value="{{ old('nama_pengantin_pria') }}" required
                                   placeholder="{{ Auth::user()->isKhitanan() ? 'Contoh: Ananda Rayhan' : 'Contoh: Luthfi' }}"
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">{{ Auth::user()->isKhitanan() ? 'Nama Orang Tua / Wali' : 'Nama Pengantin Wanita' }}<span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_pengantin_istri" value="{{ old('nama_pengantin_istri') }}" required
                                   placeholder="{{ Auth::user()->isKhitanan() ? 'Contoh: Bapak Feri & Ibu Ani' : 'Contoh: Hamidah' }}"
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">{{ Auth::user()->isKhitanan() ? 'Tanggal Acara Khitanan' : 'Tanggal Pernikahan' }}<span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_pernikahan" value="{{ old('tanggal_pernikahan') }}" required
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Acara<span class="text-rose-500">*</span></label>
                            <textarea id="alamat" name="alamat" rows="2" required placeholder="Masukkan alamat lengkap acara..."
                                      class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">{{ old('alamat') }}</textarea>
                            
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" id="cariLokasi" 
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 border border-transparent rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                                    <i class="fas fa-search"></i> Cari dari Alamat Teks
                                </button>
                                <button type="button" id="btnTogglePaste"
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-sm">
                                    <i class="fas fa-paste text-indigo-500"></i> Tempel Koordinat Google Maps
                                </button>
                            </div>

                            <!-- Input Tempel Koordinat -->
                            <div id="pasteCoordsBox" class="hidden mt-3 p-3 bg-indigo-50/70 border border-indigo-100 rounded-xl space-y-2">
                                <label class="block text-xs font-bold text-indigo-900">Tempel Koordinat Google Maps (lat, lng):</label>
                                <div class="flex gap-2">
                                    <input type="text" id="pasteCoordsInput" placeholder="Contoh: -6.200000, 106.816666 atau link maps" 
                                           class="flex-1 px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-xs font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <button type="button" id="applyPasteCoords" 
                                            class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition-colors">
                                        Terapkan
                                    </button>
                                </div>
                                <p class="text-[11px] text-indigo-600">Tip: Buka Google Maps, klik kanan/tahan titik lokasi acara Anda, lalu salin angka koordinat (contoh: <code>-6.914744, 107.609810</code>) dan tempel di sini.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Latitude</label>
                                <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                       class="block w-full px-3 py-2 border border-slate-200 bg-white text-slate-900 rounded-lg font-mono sm:text-xs focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Longitude</label>
                                <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                       class="block w-full px-3 py-2 border border-slate-200 bg-white text-slate-900 rounded-lg font-mono sm:text-xs focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">{{ Auth::user()->isKhitanan() ? 'Mulai Acara Inti' : 'Mulai Akad' }}<span class="text-rose-500">*</span></label>
                                <input type="time" name="mulai_akad" value="{{ old('mulai_akad') }}" required
                                       class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">{{ Auth::user()->isKhitanan() ? 'Selesai Acara Inti' : 'Selesai Akad' }}<span class="text-rose-500">*</span></label>
                                <input type="time" name="selesai_akad" value="{{ old('selesai_akad') }}" required
                                       class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">{{ Auth::user()->isKhitanan() ? 'Mulai Syukuran / Resepsi' : 'Mulai Resepsi' }}<span class="text-rose-500">*</span></label>
                            <input type="time" name="mulai_resepsi" value="{{ old('mulai_resepsi') }}" required
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>

                        <!-- Sesi Acara Malam / Tambahan (Opsional) -->
                        <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-xl space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    <i class="fas fa-moon text-indigo-500 mr-1"></i> Sesi Acara Malam / Tambahan (Opsional)
                                </label>
                                <span class="text-[11px] bg-indigo-50 text-indigo-600 font-semibold px-2 py-0.5 rounded-full">Kartu ke-3</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Sesi Acara:</label>
                                <input type="text" name="nama_acara_3" value="{{ old('nama_acara_3') }}" placeholder="Contoh: Resepsi Malam / Ramah Tamah"
                                       class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs transition-all">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Mulai Malam:</label>
                                    <input type="time" name="mulai_acara_3" value="{{ old('mulai_acara_3') }}"
                                           class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-slate-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Selesai (Opsional):</label>
                                    <input type="time" name="selesai_acara_3" value="{{ old('selesai_acara_3') }}"
                                           class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-slate-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Pesan Kartu Acara Malam (Mendukung Enter / Baris Baru):</label>
                                <textarea name="keterangan_acara_3" rows="2" placeholder="Contoh: Kehadiran Anda merupakan kehormatan terbesar bagi kami."
                                       class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs transition-all">{{ old('keterangan_acara_3') }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi / Catatan Acara (Mendukung Enter / Paragraf Baru):</label>
                            <textarea name="deskripsi" rows="4" placeholder="Masukkan keterangan acara atau rincian nama (tekan Enter untuk membuat baris / paragraf baru)..."
                                      class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">{{ old('deskripsi') }}</textarea>
                            <p class="text-[11px] text-slate-500 mt-1">💡 Tips: Setiap kali Anda menekan tombol <strong>Enter</strong>, teks akan otomatis tampil sebagai baris / paragraf baru di halaman undangan.</p>
                        </div>
                    </div>
                </div>

                <!-- Section Doa & Salam (Support Bahasa Arab) -->
                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 mb-4">
                        <h4 class="text-md font-bold text-indigo-900 flex items-center gap-2">
                            <i class="fas fa-quran text-indigo-600"></i> Konten Doa & Salam (Suport Bahasa Arab)
                        </h4>
                        <p class="text-xs text-indigo-700 mt-1">Anda dapat me-custom teks ucapan, salam, dan ayat/kaligrafi Arab yang akan tampil di halaman undangan. Jika dikosongkan, teks default akan digunakan.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Teks Arab / Kaligrafi / Ayat Doa (Suport Bahasa Arab):</label>
                        <textarea name="teks_arab" rows="2" dir="rtl" placeholder="بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ"
                                  class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg font-serif transition-all">{{ old('teks_arab', 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Salam Pembuka:</label>
                            <input type="text" name="salam_pembuka" value="{{ old('salam_pembuka', "Assalamu'alaikum Warahmatullahi Wabarakatuh") }}"
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Salam Penutup:</label>
                            <input type="text" name="salam_penutup" value="{{ old('salam_penutup', "Wassalamu'alaikum Warahmatullahi Wabarakatuh") }}"
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Teks Pembuka (Paragraf 1):</label>
                        <textarea name="teks_pembuka" rows="3" placeholder="Dengan memohon rahmat dan ridho Allah SWT..."
                                  class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">{{ old('teks_pembuka', 'Dengan memohon rahmat dan ridho Allah SWT yang telah menciptakan Makhluk-Nya secara berpasang-pasangan, kami bermaksud menyelenggarakan acara Walimatul Ursy.') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Teks Penutup (Paragraf 2):</label>
                        <textarea name="teks_penutup" rows="3" placeholder="Kami memohon do'a restu agar menjadi keluarga..."
                                  class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">{{ old('teks_penutup', 'Kami memohon do\'a restu agar menjadi keluarga yang Sakinah Mawaddah Warahmah. Atas perhatiannya, kami ucapkan terimakasih.') }}</textarea>
                    </div>
                </div>

                <!-- Google Map Preview & Direct Search -->
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <label class="block text-sm font-semibold text-slate-700">Preview Peta Lokasi (Geser marker merah atau klik di mana saja pada peta):</label>
                    </div>

                    <!-- Direct Search Bar Above Map -->
                    <div class="relative">
                        <div class="flex gap-2">
                            <input type="text" id="mapSearchInput" placeholder="Cari nama gedung, jalan, atau lokasi di sini (misal: Gedung Kartika Bandung)..." 
                                   class="flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <button type="button" id="btnMapSearch" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center gap-1.5 flex-shrink-0">
                                <i class="fas fa-search"></i> Cari Peta
                            </button>
                        </div>
                        <div id="searchResults" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-[9999] max-h-60 overflow-y-auto divide-y divide-slate-100 text-sm"></div>
                    </div>

                    <div id="map" class="h-80 w-full rounded-xl border border-slate-200 shadow-inner overflow-hidden"></div>
                </div>

                <div class="border-t border-slate-100 pt-6 flex flex-col sm:flex-row justify-between gap-4">
                    <a href="{{ route('info.index') }}" 
                       class="inline-flex justify-center items-center gap-1.5 px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center items-center gap-1.5 px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-save"></i> Simpan Info
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
{{-- Leaflet.js CSS: GRATIS, tanpa API key --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = parseFloat(document.getElementById('latitude').value) || -6.200000;
        const defaultLng = parseFloat(document.getElementById('longitude').value) || 106.816666;

        // Init Leaflet Map
        const map = L.map('map').setView([defaultLat, defaultLng], 16);

        // OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        }).addTo(map);

        // Draggable Marker
        const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        if (document.getElementById('latitude').value) {
            marker.bindPopup(`<b>Lokasi tersimpan:</b><br>${defaultLat.toFixed(6)}, ${defaultLng.toFixed(6)}`).openPopup();
        }

        // Update coordinates when marker is dragged
        marker.on('dragend', function (e) {
            const { lat, lng } = e.target.getLatLng();
            setLatLng(lat, lng);
        });

        // Update coordinates when map is clicked
        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            setLatLng(lat, lng);
        });

        function setLatLng(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);
        }

        // Manual coordinate input change handler
        document.getElementById('latitude').addEventListener('change', updateMapFromInputs);
        document.getElementById('longitude').addEventListener('change', updateMapFromInputs);

        function updateMapFromInputs() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                map.setView([lat, lng], 17);
                marker.setLatLng([lat, lng]);
                marker.bindPopup(`<b>Koordinat:</b><br>${lat.toFixed(7)}, ${lng.toFixed(7)}`).openPopup();
            }
        }

        // Toggle Paste Coords Box
        const btnTogglePaste = document.getElementById('btnTogglePaste');
        if (btnTogglePaste) {
            btnTogglePaste.addEventListener('click', function() {
                const box = document.getElementById('pasteCoordsBox');
                box.classList.toggle('hidden');
            });
        }

        // Apply Paste Coords
        const applyPasteBtn = document.getElementById('applyPasteCoords');
        if (applyPasteBtn) {
            applyPasteBtn.addEventListener('click', function() {
                const val = document.getElementById('pasteCoordsInput').value.trim();
                const match = val.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
                if (match) {
                    const lat = parseFloat(match[1]);
                    const lng = parseFloat(match[2]);
                    map.setView([lat, lng], 17);
                    marker.setLatLng([lat, lng]);
                    setLatLng(lat, lng);
                    marker.bindPopup(`<b>Koordinat Google Maps:</b><br>${lat.toFixed(7)}, ${lng.toFixed(7)}`).openPopup();
                } else {
                    alert('Format koordinat tidak valid. Gunakan contoh: -6.200000, 106.816666');
                }
            });
        }

        // Multi-stage Geocoding
        document.getElementById('cariLokasi').addEventListener('click', function () {
            const alamat = document.getElementById('alamat').value.trim();
            performGeocode(alamat, this);
        });

        document.getElementById('btnMapSearch').addEventListener('click', function () {
            const query = document.getElementById('mapSearchInput').value.trim();
            performGeocode(query, this);
        });

        document.getElementById('mapSearchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performGeocode(this.value.trim(), document.getElementById('btnMapSearch'));
            }
        });

        function performGeocode(query, btn) {
            if (!query) {
                alert('Masukkan alamat atau nama lokasi terlebih dahulu.');
                return;
            }

            const originalHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
            }

            const searchResultsEl = document.getElementById('searchResults');
            searchResultsEl.innerHTML = '';
            searchResultsEl.classList.add('hidden');

            function cleanQuery(str) {
                return str
                    .replace(/\b(rt|rw|no|nomor|gang|gg|blok|kav|kavling)\b\.?\s*\w+/gi, '')
                    .replace(/[\/\#\-\,\.]/g, ' ')
                    .replace(/\s+/g, ' ')
                    .trim();
            }

            const q1 = query.trim();
            const q2 = cleanQuery(query);

            const url1 = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q1)}&limit=5&countrycodes=id`;

            fetch(url1, { headers: { 'Accept-Language': 'id' } })
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        renderSearchResults(data);
                    } else if (q2 && q2 !== q1) {
                        const url2 = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q2)}&limit=5&countrycodes=id`;
                        return fetch(url2, { headers: { 'Accept-Language': 'id' } })
                            .then(res => res.json())
                            .then(data2 => {
                                if (data2 && data2.length > 0) {
                                    renderSearchResults(data2);
                                } else {
                                    alert('Alamat/lokasi tidak ditemukan secara otomatis. Silakan cari nama tempat/kota di kolom pencarian peta di atas, atau klik langsung posisi acara pada peta.');
                                }
                            });
                    } else {
                        alert('Alamat/lokasi tidak ditemukan secara otomatis. Silakan cari nama tempat/kota di kolom pencarian peta di atas, atau klik langsung posisi acara pada peta.');
                    }
                })
                .catch(() => alert('Gagal terhubung ke layanan peta. Periksa koneksi internet Anda.'))
                .finally(() => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    }
                });
        }

        function renderSearchResults(results) {
            const searchResultsEl = document.getElementById('searchResults');
            searchResultsEl.innerHTML = '';

            if (results.length === 1) {
                applyResult(results[0]);
                return;
            }

            results.forEach(item => {
                const div = document.createElement('div');
                div.className = 'px-4 py-2.5 hover:bg-indigo-50 cursor-pointer transition-colors flex items-center justify-between text-slate-800 text-xs';
                div.innerHTML = `<div><i class="fas fa-map-marker-alt text-rose-500 mr-2"></i><strong>${item.display_name}</strong></div><span class="text-indigo-600 font-semibold text-[11px]">Pilih →</span>`;
                div.addEventListener('click', function() {
                    applyResult(item);
                    searchResultsEl.classList.add('hidden');
                });
                searchResultsEl.appendChild(div);
            });

            searchResultsEl.classList.remove('hidden');
            applyResult(results[0]);
        }

        function applyResult(item) {
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lon);
            map.setView([lat, lng], 17);
            marker.setLatLng([lat, lng]);
            setLatLng(lat, lng);
            marker.bindPopup(`<b>${item.display_name}</b>`).openPopup();
        }
    });
</script>
@endpush
