@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ Auth::user()->isKhitanan() ? 'Info Acara Khitan 👦' : 'Info Pernikahan 💍' }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ Auth::user()->isKhitanan() ? 'Kelola tanggal, waktu syukuran khitan, dan peta lokasi acara' : 'Kelola data dasar pernikahan, waktu acara, dan peta lokasi' }}
            </p>
        </div>
        @if($infos->isEmpty())
            <div class="flex-shrink-0">
                <a class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" 
                   href="{{ route('info.create') }}" title="Tambah Info" role="button">
                    <i class="fas fa-plus"></i> {{ Auth::user()->isKhitanan() ? 'Tambah Info Acara' : 'Tambah Info' }}
                </a>
            </div>
        @endif
    </div>

    @if ($infos->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center space-y-4">
            <div class="inline-flex items-center justify-center p-4 bg-indigo-50 text-indigo-600 rounded-full shadow-inner">
                <i class="fas fa-info-circle text-4xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-slate-950">Belum Ada Informasi Acara</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto">
                    {{ Auth::user()->isKhitanan() ? 'Tambahkan tanggal khitanan, waktu syukuran/resepsi, dan lokasi acara.' : 'Tambahkan tanggal pernikahan, waktu akad/resepsi, dan peta lokasi agar tamu undangan mengetahuinya.' }}
                </p>
            </div>
            <a class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" 
               href="{{ route('info.create') }}">
                <i class="fas fa-plus"></i> Tambah Info Acara
            </a>
        </div>
    @else
        @foreach ($infos as $info)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ Auth::user()->isKhitanan() ? 'Detail Informasi Acara Khitan' : 'Detail Informasi Pernikahan' }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('info.edit', $info) }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-sm font-semibold transition-colors">
                            <i class="fas fa-edit"></i> Edit Info
                        </a>
                        <button class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-sm font-semibold transition-colors" 
                                onclick="confirmDelete({{ $info->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                        <form id="delete-form-{{ $info->id }}" action="{{ route('info.destroy', $info) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Kiri: Nama & Tanggal -->
                    <div class="space-y-6">
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 space-y-4">
                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider">{{ Auth::user()->isKhitanan() ? 'Profil Utama' : 'Pengantin' }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-slate-400 block font-semibold">{{ Auth::user()->isKhitanan() ? 'Nama Ananda:' : 'Pengantin Pria:' }}</span>
                                    <span class="text-base font-bold text-slate-900 block mt-0.5">{{ $info->nama_pengantin_pria }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 block font-semibold">{{ Auth::user()->isKhitanan() ? 'Orang Tua / Wali:' : 'Pengantin Wanita:' }}</span>
                                    <span class="text-base font-bold text-slate-900 block mt-0.5">{{ $info->nama_pengantin_istri }}</span>
                                </div>
                            </div>
                            
                            <hr class="border-slate-100">

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold">Tanggal Pernikahan:</span>
                                <span class="text-base font-bold text-slate-900 block mt-0.5">
                                    <i class="far fa-calendar-alt text-indigo-500 mr-1.5"></i>
                                    {{ \Carbon\Carbon::parse($info->tanggal_pernikahan)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 space-y-3">
                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Alamat & Lokasi</h4>
                            <div>
                                <span class="text-xs text-slate-400 block font-semibold font-semibold">Alamat Acara:</span>
                                <span class="text-sm text-slate-700 block mt-1 leading-relaxed">{{ $info->alamat }}</span>
                            </div>
                            
                            @if($info->deskripsi)
                                <div class="pt-2 border-t border-slate-100">
                                    <span class="text-xs text-slate-400 block font-semibold">Deskripsi / Catatan Tambahan:</span>
                                    <div class="text-sm text-slate-600 block mt-1 leading-relaxed whitespace-pre-line">{!! nl2br(e($info->deskripsi)) !!}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan: Waktu & Peta -->
                    <div class="space-y-6">
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 space-y-4">
                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Detail Waktu</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-inner">
                                    <span class="text-xs text-slate-400 block font-bold uppercase tracking-wider">Mulai Akad</span>
                                    <span class="text-lg font-extrabold text-slate-900 block mt-1"><i class="far fa-clock text-indigo-500 mr-1.5"></i>{{ $info->mulai_akad }}</span>
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-inner">
                                    <span class="text-xs text-slate-400 block font-bold uppercase tracking-wider">Selesai Akad</span>
                                    <span class="text-lg font-extrabold text-slate-900 block mt-1"><i class="far fa-clock text-indigo-500 mr-1.5"></i>{{ $info->selesai_akad }}</span>
                                </div>
                            </div>
                            <div class="bg-indigo-50/30 p-3.5 rounded-xl border border-indigo-100">
                                <span class="text-xs text-indigo-700 block font-bold uppercase tracking-wider">Mulai Resepsi</span>
                                <span class="text-lg font-extrabold text-indigo-950 block mt-1"><i class="far fa-clock text-indigo-600 mr-1.5"></i>{{ $info->mulai_resepsi }}</span>
                            </div>
                            @if($info->mulai_acara_3)
                            <div class="bg-purple-50/40 p-3.5 rounded-xl border border-purple-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-purple-700 block font-bold uppercase tracking-wider">{{ $info->nama_acara_3 ?: 'Sesi Acara Malam' }}</span>
                                    <span class="text-[10px] bg-purple-100 text-purple-700 font-semibold px-2 py-0.5 rounded-full">Kartu ke-3</span>
                                </div>
                                <span class="text-lg font-extrabold text-purple-950 block mt-1">
                                    <i class="far fa-clock text-purple-600 mr-1.5"></i>{{ $info->mulai_acara_3 }} {{ $info->selesai_acara_3 ? '- ' . $info->selesai_acara_3 : '- Selesai' }}
                                </span>
                                @if($info->keterangan_acara_3)
                                    <div class="text-xs text-purple-600 mt-1 italic whitespace-pre-line">{!! nl2br(e($info->keterangan_acara_3)) !!}</div>
                                @endif
                            </div>
                            @endif
                        </div>

                        @if($info->latitude && $info->longitude)
                            <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 space-y-4">
                                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Peta Lokasi</h4>
                                <div class="rounded-xl overflow-hidden border border-slate-200 shadow-inner">
                                    <iframe width="100%" height="220" style="border:0;" loading="lazy" allowfullscreen
                                        src="https://maps.google.com/maps?q={{ $info->latitude }},{{ $info->longitude }}&z=15&output=embed">
                                    </iframe>
                                </div>
                                <a class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold text-indigo-700 bg-white hover:bg-slate-50 transition-colors shadow-sm"
                                    href="https://www.google.com/maps/search/?api=1&query={{ $info->latitude }},{{ $info->longitude }}"
                                    target="_blank">
                                    <i class="fas fa-map-marker-alt"></i> Buka di Google Maps
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data informasi pernikahan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection
