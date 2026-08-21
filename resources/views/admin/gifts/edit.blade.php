@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Rekening Kado</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi rekening hadiah digital Anda</p>
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

    <div class="max-w-3xl bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Edit Informasi: {{ $gift->nama_bank }}</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('gifts.update', $gift) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1" for="nama">Nama Penerima<span class="text-rose-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $gift->name ?? $gift->nama) }}" required
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1" for="nama_bank">Nama Bank / E-Wallet<span class="text-rose-500">*</span></label>
                            <input type="text" id="nama_bank" name="nama_bank" value="{{ old('nama_bank', $gift->nama_bank) }}" required
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1" for="no_rek">Nomor Rekening / HP<span class="text-rose-500">*</span></label>
                            <input type="text" id="no_rek" name="no_rek" value="{{ old('no_rek', $gift->no_rek) }}" required
                                   class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1" for="bg_color">Warna Background Kartu<span class="text-rose-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="bg_color_picker" value="{{ old('bg_color', $gift->bg_color ?? '#3e4b6d') }}"
                                       class="h-10 w-14 cursor-pointer rounded-lg border border-slate-200 p-1"
                                       oninput="document.getElementById('bg_color').value = this.value; updateCardPreview(this.value);">
                                <input type="text" id="bg_color" name="bg_color" value="{{ old('bg_color', $gift->bg_color ?? '#3e4b6d') }}" required
                                       class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                                       placeholder="#3e4b6d" oninput="document.getElementById('bg_color_picker').value = this.value; updateCardPreview(this.value);">
                            </div>
                            <!-- Color Presets -->
                            <div class="mt-2.5 flex flex-wrap gap-2 items-center text-xs">
                                <span class="text-slate-400 font-medium me-1">Pilihan Warna:</span>
                                <button type="button" onclick="setColor('#3e4b6d')" class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" style="background:#3e4b6d" title="Navy Blue"></button>
                                <button type="button" onclick="setColor('#1e293b')" class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" style="background:#1e293b" title="Midnight Slate"></button>
                                <button type="button" onclick="setColor('#065f46')" class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" style="background:#065f46" title="Emerald Green"></button>
                                <button type="button" onclick="setColor('#800020')" class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" style="background:#800020" title="Burgundy"></button>
                                <button type="button" onclick="setColor('#b39247')" class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" style="background:#b39247" title="Elegant Gold"></button>
                                <button type="button" onclick="setColor('#4c1d95')" class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" style="background:#4c1d95" title="Royal Purple"></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Card Preview -->
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50">
                    <label class="block text-xs font-semibold uppercase text-slate-400 mb-2">Pratinjau Tampilan Kartu</label>
                    <div id="card-preview" class="p-5 rounded-2xl text-white shadow-md transition-all duration-300" style="background-color: <?= old('bg_color', $gift->bg_color ?? '#3e4b6d') ?>;">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider opacity-80" id="preview-bank">{{ $gift->nama_bank }}</span>
                            <div class="w-8 h-6 bg-yellow-400/80 rounded-md"></div>
                        </div>
                        <div class="font-mono text-lg font-bold tracking-widest my-2" id="preview-rek">{{ $gift->no_rek }}</div>
                        <div class="text-[10px] uppercase opacity-70">Atas Nama</div>
                        <div class="text-sm font-semibold" id="preview-nama">{{ $gift->nama }}</div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1" for="deskripsi">Deskripsi / Catatan (Opsional):</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                              placeholder="Catatan tambahan (opsional)">{{ old('deskripsi', $gift->deskripsi) }}</textarea>
                </div>

                <div class="border-t border-slate-100 pt-6 flex flex-col sm:flex-row justify-between gap-4">
                    <a href="{{ route('gifts.index') }}" 
                       class="inline-flex justify-center items-center gap-1.5 px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center items-center gap-1.5 px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setColor(hex) {
            document.getElementById('bg_color_picker').value = hex;
            document.getElementById('bg_color').value = hex;
            updateCardPreview(hex);
        }

        function updateCardPreview(color) {
            document.getElementById('card-preview').style.background = color;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputNama = document.getElementById('nama');
            const inputBank = document.getElementById('nama_bank');
            const inputRek  = document.getElementById('no_rek');

            if (inputNama) {
                inputNama.addEventListener('input', function() {
                    document.getElementById('preview-nama').innerText = this.value || 'Nama Penerima';
                });
            }
            if (inputBank) {
                inputBank.addEventListener('input', function() {
                    document.getElementById('preview-bank').innerText = this.value || 'Nama Bank / E-Wallet';
                });
            }
            if (inputRek) {
                inputRek.addEventListener('input', function() {
                    document.getElementById('preview-rek').innerText = this.value || '1234 5678 9000';
                });
            }
        });
    </script>
@endsection
